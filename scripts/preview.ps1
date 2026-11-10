<#
.SYNOPSIS
    Live preview gate, steps 1 and 2: load the latest code, then check the
    Laragon preview serves it. Run before any development work.

.DESCRIPTION
    1. Stops if tracked files have uncommitted changes. Fetches origin, fast-
       forwards the branch, and merges origin/main into it (a merge commit, never
       a rebase; a conflict is aborted and reported). Nothing is pushed.
    2. Runs composer install or npm ci when a lock file is newer than the
       installed dependencies, and reports migrations that have not run (it
       never runs them).
    3. Checks Laragon is running and the site serves the checked-out code: for
       a Laravel app, /healthz shows the revision and PHP through FastCGI; for a
       static site, /VERSION matches the VERSION file.
    4. Starts the dev server (Vite or Browsersync) in its own window if nothing
       listens on its port.

    Opening the page in Cursor's browser is the agent's step 3.

    Exit codes: 0 ready, 1 blocked (the message says why), 3 needs a decision.

.EXAMPLE
    powershell -NoProfile -ExecutionPolicy Bypass -File scripts\preview.ps1
#>
[CmdletBinding()]
param(
    [switch]$NoSync,
    [switch]$NoMergeMain,
    [switch]$NoDevServer,
    [string]$LaragonRoot = 'C:\laragon'
)

# This repository's Laragon values (docs/laragon/profile.md).
$Config = @{
    AppUrl     = 'http://apes-cic-website.test'
    Folder     = 'C:\laragon\www\apes-cic-website'
    Php        = '8.4'
    Laravel    = $false
    DevPort    = 3104
    HealthPath = '/VERSION'
    DevCommand = 'npm run dev'
}

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
        $stream = $_.Exception.Response.GetResponseStream()
        if ($null -eq $stream) { return $null }
        return (New-Object IO.StreamReader $stream).ReadToEnd()
    }
}

$repo = Split-Path $PSScriptRoot -Parent
$phpDir = Get-ChildItem "$LaragonRoot\bin\php" -Directory -ErrorAction SilentlyContinue |
    Where-Object { $_.Name -match "^php-$([regex]::Escape($Config.Php))\.\d+(-\d+)?-(nts-)?Win32" } |
    Sort-Object @{ Expression = { $_.Name -notmatch '-nts-' }; Ascending = $true }, @{ Expression = { $_.Name }; Descending = $true } | Select-Object -First 1
if (-not $phpDir) { Stop-Preview 1 "Laragon has no PHP $($Config.Php). Run scripts\laragon-setup.ps1." }
$nodeDir = Get-ChildItem "$LaragonRoot\bin\nodejs" -Directory -ErrorAction SilentlyContinue | Where-Object { $_.Name -match '^node-v?22(\D|$)' } | Select-Object -First 1
$env:Path = "$($phpDir.FullName);$LaragonRoot\bin\composer;$(if ($nodeDir) { $nodeDir.FullName });" + $env:Path
if (-not $env:XDEBUG_MODE) { $env:XDEBUG_MODE = 'off' }

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

    # 2. Dependencies and migrations --------------------------------------------
    Write-Step 'Dependencies'
    if (Test-Path 'composer.lock') {
        $installed = Join-Path $repo 'vendor\composer\installed.json'
        if (-not (Test-Path $installed) -or (Get-Item 'composer.lock').LastWriteTime -gt (Get-Item $installed).LastWriteTime) {
            $run = Invoke-Native { php "$LaragonRoot\bin\composer\composer.phar" install --no-interaction --no-progress }
            if ($run.Code -ne 0) { Stop-Preview 1 "composer install failed: $($run.Output)" }
            Write-Ok 'Ran composer install'
        } else { Write-Ok 'Composer dependencies are current' }
    }
    if (Test-Path 'package-lock.json') {
        $npmMarker = Join-Path $repo 'node_modules\.package-lock.json'
        if (-not (Test-Path $npmMarker) -or (Get-Item 'package-lock.json').LastWriteTime -gt (Get-Item $npmMarker).LastWriteTime) {
            $run = Invoke-Native { npm ci --no-audit --no-fund }
            if ($run.Code -ne 0) { Stop-Preview 1 "npm ci failed: $($run.Output)" }
            Write-Ok 'Ran npm ci (restart the dev server if it was running)'
        } else { Write-Ok 'npm dependencies are current' }
    }
    if ($Config.Laravel) {
        $pending = Invoke-Native { php artisan migrate:status --pending --no-ansi }
        if ($pending.Code -eq 0 -and $pending.Output -match '\bPending\b' -and $pending.Output -notmatch 'No pending migrations') {
            Write-Warn 'There are migrations that have not run. Ask before running php artisan migrate.'
        } else { Write-Ok 'No pending migrations' }
    }

    # 3. Laragon and the preview -------------------------------------------------
    Write-Step "Laragon preview $($Config.AppUrl)"
    $needed = if ($Config.Laravel) { @('laragon', 'httpd', 'mysqld') } else { @('laragon', 'httpd') }
    $missing = @($needed | Where-Object { -not (Get-Process $_ -ErrorAction SilentlyContinue) })
    if ($missing.Count -gt 0) { Stop-Preview 1 "Not running: $($missing -join ', '). Click Start All in Laragon." }
    if ($Config.Laravel) {
        $health = Get-Page "$($Config.AppUrl)$($Config.HealthPath)" | ConvertFrom-Json
        if ($null -eq $health -or $null -eq $health.local) { Stop-Preview 1 "$($Config.AppUrl)$($Config.HealthPath) did not answer with the local block. Is APP_ENV=local in .env, and has Laragon been reloaded?" }
        if ($health.local.git_revision -ne $short) {
            Invoke-Native { php artisan optimize:clear } | Out-Null
            $health = Get-Page "$($Config.AppUrl)$($Config.HealthPath)" | ConvertFrom-Json
        }
        if ($health.local.git_revision -ne $short) { Stop-Preview 1 "The preview serves $($health.local.git_revision), not $short. Check the junction $($Config.Folder) points at this clone." }
        if ($health.local.php_sapi -ne 'cgi-fcgi') { Stop-Preview 1 "The preview runs PHP as $($health.local.php_sapi), not FastCGI (cgi-fcgi). Run scripts\laragon-setup.ps1 to check the PHP handler." }
        if ($health.local.php -notlike "$($Config.Php).*") { Write-Warn "The preview runs PHP $($health.local.php), not the profile's $($Config.Php). scripts\laragon-setup.ps1 says how to fix it." }
        Write-Ok "Serves $short on PHP $($health.local.php) through FastCGI"
        if ($health.status -ne 'ok') { Write-Warn "$($Config.HealthPath) reports $($health.status). The preview still works; start the Laragon service it needs if the task needs it." }
    } else {
        $served = "$(Get-Page "$($Config.AppUrl)/VERSION")".Trim()
        $expected = (Get-Content (Join-Path $repo 'public\VERSION') -Raw).Trim()
        if (-not $served) { Stop-Preview 1 "$($Config.AppUrl)/VERSION did not answer. Check the junction $($Config.Folder) points at this clone and Laragon has been reloaded." }
        if ($served -ne $expected) { Stop-Preview 1 "The preview serves VERSION $served, not $expected from this checkout. Check the junction $($Config.Folder) points at this clone." }
        Write-Ok "Serves this checkout (VERSION $served)"
    }

    # 4. Dev server ------------------------------------------------------------
    Write-Step "Dev server on port $($Config.DevPort)"
    if (Get-NetTCPConnection -State Listen -LocalPort $Config.DevPort -ErrorAction SilentlyContinue) {
        Write-Ok 'Already running'
    } elseif ($NoDevServer) {
        Write-Warn "Not running (-NoDevServer). Start it with $($Config.DevCommand)."
    } else {
        Start-Process -FilePath 'cmd.exe' -ArgumentList '/c', $Config.DevCommand -WorkingDirectory $repo -WindowStyle Minimized
        Write-Ok "Started $($Config.DevCommand) in its own window"
    }

    Write-Step 'Ready'
    if ($Config.Laravel) { Write-Ok "Open $($Config.AppUrl) in Cursor's browser (live reload on port $($Config.DevPort))." } else { Write-Ok "Open http://localhost:$($Config.DevPort) in Cursor's browser (Browsersync proxying $($Config.AppUrl), reloads on save)." }
    if ($before -ne $head) { Write-Ok "Code moved from $($before.Substring(0, 12)) to $short during the sync." }
} finally {
    Pop-Location
}
