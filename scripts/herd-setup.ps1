<#
.SYNOPSIS
    Sets up or repairs this website's Laravel Herd site. Safe to run twice; touches only this site.

.DESCRIPTION
    Reads docs/herd/profile.json, checks Herd and its nginx are running, links this clone in Herd as
    <project>.test if it is not linked, isolates it to the profile's PHP version, runs npm ci with Herd's
    Node when node_modules is missing, and checks the site serves this clone's public/ folder.

    Exit codes: 0 ready, 1 blocked, 3 a decision or a click in Herd is needed.
#>
[CmdletBinding()]
param()

$ErrorActionPreference = 'Stop'
$repo = Split-Path -Parent $PSScriptRoot
$herdProfile = Get-Content (Join-Path $repo 'docs\herd\profile.json') -Raw | ConvertFrom-Json
$herdBin = Join-Path $env:USERPROFILE '.config\herd\bin'

function Write-Ok([string] $Message) { Write-Host "  ok   $Message" -ForegroundColor Green }
function Write-Warn([string] $Message) { Write-Host "  warn $Message" -ForegroundColor Yellow }
function Stop-Setup([int] $Code, [string] $Message) {
    Write-Host "  stop $Message" -ForegroundColor Red
    exit $Code
}

function Get-HerdSite {
    $previous = $ErrorActionPreference
    $ErrorActionPreference = 'Continue'
    try { $json = (& herd site:info --json 2>$null | Out-String).Trim() } finally { $ErrorActionPreference = $previous }
    if ($LASTEXITCODE -ne 0 -or -not $json.StartsWith('{')) { return $null }
    return $json | ConvertFrom-Json
}

Write-Host "Herd set-up for $($herdProfile.appUrl)"

if (-not (Test-Path (Join-Path $herdBin 'herd.bat'))) { Stop-Setup 1 "Laravel Herd is not installed ($herdBin\herd.bat is missing)." }
$node = Get-ChildItem (Join-Path $herdBin 'nvm') -Directory -Filter "v$($herdProfile.node).*" -ErrorAction SilentlyContinue |
    Sort-Object { [version]$_.Name.TrimStart('v') } -Descending | Select-Object -First 1
$env:Path = "$herdBin;$(if ($node) { $node.FullName });" + $env:Path

if ((& herd status 2>&1 | Out-String) -notmatch 'NGINX\s*\|\s*active') { Stop-Setup 3 'Herd is not running. Start the Herd app, then run this again.' }
Write-Ok 'Herd and its nginx are running'

Push-Location $repo
try {
    $site = Get-HerdSite
    if ($site -and $site.name -ne $herdProfile.project) {
        Stop-Setup 1 "This clone is linked in Herd as $($site.name), not $($herdProfile.project). Unlink it in Herd first; this script does not rename sites."
    }
    if (-not $site) {
        & herd link $herdProfile.project | Out-Null
        $site = Get-HerdSite
        if (-not $site) { Stop-Setup 1 "herd link $($herdProfile.project) did not create the site." }
        Write-Ok "Linked $repo as $($herdProfile.site)"
    } else {
        Write-Ok "Linked as $($site.host)"
    }
    if ($site.path.TrimEnd('\') -ne $repo.TrimEnd('\')) {
        Stop-Setup 1 "$($herdProfile.site) points at $($site.path), not this clone. Unlink that site in Herd yourself; this script does not replace it."
    }

    if ($site.isolatedPhpVersion -ne $herdProfile.php) {
        if (-not ($site.availablePhpVersions | Where-Object { $_.version -eq $herdProfile.php -and $_.installed })) {
            Stop-Setup 3 "Herd has no PHP $($herdProfile.php). Ask Bambie before running: herd php:install $($herdProfile.php)"
        }
        & herd isolate $herdProfile.php | Out-Null
        Write-Ok "Isolated to PHP $($herdProfile.php)"
    } else {
        Write-Ok "PHP $($herdProfile.php) (isolated)"
    }

    if (-not (Test-Path (Join-Path $repo 'node_modules'))) {
        if (-not $node) { Stop-Setup 3 "Herd has no Node $($herdProfile.node). Ask Bambie before running: herd node:install $($herdProfile.node)" }
        npm ci --no-audit --no-fund
        if ($LASTEXITCODE) { Stop-Setup 1 'npm ci failed.' }
        Write-Ok 'npm ci'
    } else {
        Write-Ok 'node_modules present'
    }
} finally {
    Pop-Location
}

try {
    $served = (Invoke-WebRequest -UseBasicParsing -TimeoutSec 10 "$($herdProfile.appUrl)/VERSION").Content
    if ($served -is [byte[]]) { $served = [Text.Encoding]::UTF8.GetString($served) }
    $served = "$served".Trim()
} catch {
    Stop-Setup 3 "$($herdProfile.appUrl) did not answer. Check Herd is running, then run this again."
}
$expected = (Get-Content (Join-Path $repo 'public\VERSION') -Raw).Trim()
if ($served -ne $expected) { Stop-Setup 1 "$($herdProfile.appUrl)/VERSION is $served, not $expected from this clone." }
Write-Ok "$($herdProfile.appUrl) serves this clone (VERSION $served)"
Write-Host 'Ready. Run scripts\preview.ps1 for the live preview.'
exit 0
