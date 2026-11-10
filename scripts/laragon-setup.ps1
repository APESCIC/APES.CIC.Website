<#
.SYNOPSIS
    Sets up or repairs this website's Laragon project. Safe to run twice; touches only this project.

.DESCRIPTION
    Reads docs/laragon/profile.json, links C:\laragon\www\<project> to this clone, checks Laragon's
    PHP 8.4 NTS build and the FastCGI handler, runs npm ci when node_modules is missing, and checks
    that the virtual host serves this clone's public/ folder.

    Exit codes: 0 ready, 1 blocked, 3 a decision or a click in Laragon is needed.
#>
[CmdletBinding()]
param(
    [string] $LaragonRoot = 'C:\laragon'
)

$ErrorActionPreference = 'Stop'
$repo = Split-Path -Parent $PSScriptRoot
$profile = Get-Content (Join-Path $repo 'docs\laragon\profile.json') -Raw | ConvertFrom-Json

function Write-Ok([string] $Message) { Write-Host "  ok   $Message" -ForegroundColor Green }
function Write-Warn([string] $Message) { Write-Host "  warn $Message" -ForegroundColor Yellow }
function Stop-Setup([int] $Code, [string] $Message) {
    Write-Host "  stop $Message" -ForegroundColor Red
    exit $Code
}

Write-Host "Laragon set-up for $($profile.appUrl)"

if (-not (Test-Path $LaragonRoot)) { Stop-Setup 1 "Laragon is not installed at $LaragonRoot." }

$phpRoot = Join-Path $LaragonRoot 'bin\php'
$php = Get-ChildItem $phpRoot -Directory -ErrorAction SilentlyContinue |
    Where-Object Name -Match "^php-$([regex]::Escape($profile.php))\.\d+(-\d+)?-nts-Win32" |
    Sort-Object Name -Descending | Select-Object -First 1
if ($php) { Write-Ok "PHP $($profile.php) NTS: $($php.Name)" }
else { Write-Warn "No PHP $($profile.php) NTS build in $phpRoot. Only the .php pages need it; ask Bambie before installing one." }

$httpdConf = Get-ChildItem (Join-Path $LaragonRoot 'bin\apache') -Recurse -Filter httpd.conf -ErrorAction SilentlyContinue |
    Where-Object FullName -Match '\\conf\\httpd\.conf$' | Select-Object -First 1
if ($httpdConf -and (Select-String -Path $httpdConf.FullName -Pattern '^\s*Include\s+.*fcgid\.conf' -Quiet)) {
    Write-Ok 'Apache runs PHP through FastCGI (mod_fcgid)'
} else {
    Write-Warn 'Apache does not include fcgid.conf; .php pages may run through mod_php. See docs/laragon/profile.md.'
}

$www = Join-Path $LaragonRoot "www\$($profile.project)"
if (Test-Path $www) {
    $target = (Get-Item $www).Target
    if ($target -and ((Resolve-Path $target).Path.TrimEnd('\') -ne $repo.TrimEnd('\'))) {
        Stop-Setup 1 "$www already points at $target, not this clone. Move it yourself; this script does not replace it."
    }
    Write-Ok "Junction $www"
} else {
    New-Item -ItemType Junction -Path $www -Target $repo | Out-Null
    Write-Ok "Created junction $www -> $repo"
}

if (-not (Test-Path (Join-Path $repo 'node_modules'))) {
    $node = Join-Path $LaragonRoot 'bin\nodejs\node-v22'
    if (Test-Path $node) { $env:Path = "$node;" + $env:Path }
    Push-Location $repo
    try { npm ci --no-audit --no-fund; if ($LASTEXITCODE) { Stop-Setup 1 'npm ci failed.' } }
    finally { Pop-Location }
    Write-Ok 'npm ci'
} else {
    Write-Ok 'node_modules present'
}

$vhost = Join-Path $LaragonRoot "etc\apache2\sites-enabled\auto.$($profile.site).conf"
if (-not (Test-Path $vhost)) {
    Stop-Setup 3 "Laragon has not created $($profile.site) yet. Click Menu > Apache > Reload in Laragon, then run this again."
}
$root = (Select-String -Path $vhost -Pattern 'define ROOT "([^"]+)"').Matches | Select-Object -First 1
if (-not $root -or $root.Groups[1].Value -notmatch '/public/?$') {
    Stop-Setup 3 "The Laragon virtual host for $($profile.site) does not serve public/ ($($root.Groups[1].Value)). Click Menu > Apache > Reload in Laragon, then run this again."
}
Write-Ok "Virtual host serves $($root.Groups[1].Value)"

try {
    $served = (Invoke-WebRequest -UseBasicParsing -TimeoutSec 10 "$($profile.appUrl)/VERSION").Content
    if ($served -is [byte[]]) { $served = [Text.Encoding]::UTF8.GetString($served) }
    $served = "$served".Trim()
} catch {
    Stop-Setup 3 "$($profile.appUrl) did not answer. Start Laragon (Start All), then run this again."
}
$expected = (Get-Content (Join-Path $repo 'public\VERSION') -Raw).Trim()
if ($served -ne $expected) { Stop-Setup 1 "$($profile.appUrl)/VERSION is $served, not $expected from this clone." }
Write-Ok "$($profile.appUrl) serves this clone (VERSION $served)"
Write-Host 'Ready. Run scripts\preview.ps1 for the live preview.'
exit 0
