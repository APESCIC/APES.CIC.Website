<#
.SYNOPSIS
    Live preview gate, steps 1 and 2: load the latest code, then check the
    Laravel Herd preview serves it. Run before any development work.

.DESCRIPTION
    1. Stops if tracked files have uncommitted changes. Fetches origin, fast-
       forwards the branch, and merges origin/main into it (a merge commit, never
       a rebase; a conflict is aborted and reported). Nothing is pushed.
    2. Runs npm ci when package-lock.json is newer than the installed packages.
    3. Checks Herd is running, the site is linked to this clone and isolated to
       the profile's PHP version, and /VERSION matches the VERSION file.
    4. Starts Browsersync in its own window if nothing listens on its port.

    Values come from docs/herd/profile.json. Opening the page in Cursor's
    browser is the agent's step 3.

    Exit codes: 0 ready, 1 blocked (the message says why), 3 needs a decision.

.EXAMPLE
    powershell -NoProfile -ExecutionPolicy Bypass -File scripts\preview.ps1
#>
[CmdletBinding()]
param(
    [switch]$NoSync,
    [switch]$NoMergeMain,
    [switch]$NoDevServer
)

$ErrorActionPreference = 'Stop'

function Write-Step([string]$Message) { Write-Host "==> $Message" -ForegroundColor Cyan }
function Write-Ok([string]$Message) { Write-Host "    $Message" -ForegroundColor Green }
function Write-Warn([string]$Message) { Write-Host "    WARNING: $Message" -ForegroundColor Yellow }
function Stop-Preview([int]$Code, [string]$Message) { Write-Host "    STOP: $Message" -ForegroundColor Red; exit $Code }

function Invoke-Native([scriptblock]$Command) {
    $previous = $ErrorActionPreference
    $ErrorActionPreference = 'Continue'
    try { $output = & $Command 2>&1 } finally { $ErrorActionPreference = $previous }
    return [pscustomobject]@{ Code = $LASTEXITCODE; Output = ($output | Out-String).Trim() }
}

function Get-Page([string]$Url) {
    try {
        $content = (Invoke-WebRequest $Url -UseBasicParsing -TimeoutSec 60).Content
        if ($content -is [byte[]]) { $content = [Text.Encoding]::UTF8.GetString($content) }
        return $content
    } catch {
        return $null
    }
}

$repo = Split-Path $PSScriptRoot -Parent
$herdProfile = Get-Content (Join-Path $repo 'docs\herd\profile.json') -Raw | ConvertFrom-Json
$devCommand = 'npm run dev'
$herdBin = Join-Path $env:USERPROFILE '.config\herd\bin'
if (-not (Test-Path (Join-Path $herdBin 'herd.bat'))) { Stop-Preview 1 "Laravel Herd is not installed ($herdBin\herd.bat is missing)." }
$nodeDir = Get-ChildItem (Join-Path $herdBin 'nvm') -Directory -Filter "v$($herdProfile.node).*" -ErrorAction SilentlyContinue |
    Sort-Object { [version]$_.Name.TrimStart('v') } -Descending | Select-Object -First 1
if (-not $nodeDir) { Stop-Preview 3 "Herd has no Node $($herdProfile.node). Ask Bambie before running: herd node:install $($herdProfile.node)" }
$env:Path = "$herdBin;$($nodeDir.FullName);" + $env:Path

Push-Location $repo
try {
    # 1. Latest code ------------------------------------------------------------
    Write-Step 'Latest code'
    $before = (git rev-parse HEAD).Trim()
    $branch = (git branch --show-current).Trim()
    if ($NoSync) {
        Write-Warn 'Skipped the sync (-NoSync)'
    } else {
        $dirty = @(git status --porcelain --untracked-files=no)
        if ($dirty.Count -gt 0) { Stop-Preview 3 "$($dirty.Count) tracked files have uncommitted changes. Commit them or decide what to do before syncing." }
        $fetch = Invoke-Native { git fetch origin --prune --tags }
        if ($fetch.Code -ne 0) { Stop-Preview 1 "git fetch failed: $($fetch.Output)" }
        $upstream = Invoke-Native { git rev-parse --abbrev-ref '@{u}' }
        if ($upstream.Code -eq 0) {
            $ff = Invoke-Native { git merge --ff-only '@{u}' }
            if ($ff.Code -ne 0) { Stop-Preview 3 "$branch and $($upstream.Output) have both moved on. Merge them (no rebase), then run this again." }
        }
        if ($branch -ne 'main' -and -not $NoMergeMain) {
            $behind = [int](git rev-list --count 'HEAD..origin/main').Trim()
            if ($behind -gt 0) {
                $merge = Invoke-Native { git merge --no-edit origin/main }
                if ($merge.Code -ne 0) {
                    Invoke-Native { git merge --abort } | Out-Null
                    Stop-Preview 3 "Merging origin/main into $branch conflicts. The merge was aborted; resolve it before starting work."
                }
                Write-Ok "Merged $behind commits from origin/main into $branch (not pushed)"
            }
        }
    }
    $head = (git rev-parse HEAD).Trim()
    $short = $head.Substring(0, 12)
    Write-Ok "$branch at $short"

    # 2. Dependencies -------------------------------------------------------------
    Write-Step 'Dependencies'
    $npmMarker = Join-Path $repo 'node_modules\.package-lock.json'
    if (-not (Test-Path $npmMarker) -or (Get-Item 'package-lock.json').LastWriteTime -gt (Get-Item $npmMarker).LastWriteTime) {
        $run = Invoke-Native { npm ci --no-audit --no-fund }
        if ($run.Code -ne 0) { Stop-Preview 1 "npm ci failed: $($run.Output)" }
        Write-Ok 'Ran npm ci (restart the dev server if it was running)'
    } else { Write-Ok 'npm dependencies are current' }

    # 3. Herd and the preview -----------------------------------------------------
    Write-Step "Herd preview $($herdProfile.appUrl)"
    $status = Invoke-Native { herd status }
    if ($status.Output -notmatch 'NGINX\s*\|\s*active') { Stop-Preview 1 'Herd is not running. Start the Herd app.' }
    $info = Invoke-Native { herd site:info --json }
    if ($info.Code -ne 0 -or -not $info.Output.StartsWith('{')) { Stop-Preview 1 "This clone is not linked in Herd. Run scripts\herd-setup.ps1." }
    $site = $info.Output | ConvertFrom-Json
    if ($site.name -ne $herdProfile.project -or $site.path.TrimEnd('\') -ne $repo.TrimEnd('\')) {
        Stop-Preview 1 "Herd serves $($site.host) from $($site.path), not $($herdProfile.site) from this clone. Run scripts\herd-setup.ps1."
    }
    if ($site.isolatedPhpVersion -ne $herdProfile.php) { Stop-Preview 1 "The site runs PHP $($site.phpVersion), not $($herdProfile.php). Run scripts\herd-setup.ps1." }
    $served = "$(Get-Page "$($herdProfile.appUrl)/VERSION")".Trim()
    $expected = (Get-Content (Join-Path $repo 'public\VERSION') -Raw).Trim()
    if (-not $served) { Stop-Preview 1 "$($herdProfile.appUrl)/VERSION did not answer. Run scripts\herd-setup.ps1." }
    if ($served -ne $expected) { Stop-Preview 1 "The preview serves VERSION $served, not $expected from this checkout. Run scripts\herd-setup.ps1." }
    Write-Ok "Serves this checkout (VERSION $served) on PHP $($site.isolatedPhpVersion)"

    # 4. Dev server ------------------------------------------------------------
    Write-Step "Dev server on port $($herdProfile.devServerPort)"
    if (Get-NetTCPConnection -State Listen -LocalPort $herdProfile.devServerPort -ErrorAction SilentlyContinue) {
        Write-Ok 'Already running'
    } elseif ($NoDevServer) {
        Write-Warn "Not running (-NoDevServer). Start it with $devCommand."
    } else {
        Start-Process -FilePath 'cmd.exe' -ArgumentList '/c', $devCommand -WorkingDirectory $repo -WindowStyle Minimized
        Write-Ok "Started $devCommand in its own window"
    }

    Write-Step 'Ready'
    Write-Ok "Open http://localhost:$($herdProfile.devServerPort) in Cursor's browser (Browsersync proxying $($herdProfile.appUrl), reloads on save)."
    if ($before -ne $head) { Write-Ok "Code moved from $($before.Substring(0, 12)) to $short during the sync." }
} finally {
    Pop-Location
}
