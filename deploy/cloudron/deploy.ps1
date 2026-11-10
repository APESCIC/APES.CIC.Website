<#
.SYNOPSIS
    Deploys one released tag of this website to its Cloudron LAMP app, with a backup, a check and a rollback.

.DESCRIPTION
    Run only after the ship gate, with a tag that has a published GitHub Release:

        deploy/cloudron/deploy.ps1 -Tag vX.Y.Z

    1. Checks the tag exists, is on origin/main, has a published Release, and that its public/VERSION
       matches the tag.
    2. Takes a Cloudron backup of the app.
    3. Packs public/ at the tag with git archive (one .tar.gz) and uploads it with cloudron push.
    4. Unpacks it next to /app/data/public, refuses if the live site has files the tag does not
       (unless -AllowRemovals), then swaps the folders with two renames.
    5. Checks https://<domain>/VERSION serves the tag's version; swaps the old folder back if not.

    Prints the time each stage took. Never prints secrets or credentials.

.PARAMETER Tag
    The release tag to deploy, for example v1.3.0. Never a branch.

.PARAMETER AllowRemovals
    Let the deploy remove live files that are not in the tag (they stay in the kept previous folder).
#>
[CmdletBinding()]
param(
    [Parameter(Mandatory)] [ValidatePattern('^v\d+\.\d+\.\d+$')] [string] $Tag,
    [string] $App = 'cfeb1a50-eb8d-4cfd-ac84-a0606c6051ec',
    [string] $Domain = 'www.apes.org.uk',
    [switch] $AllowRemovals
)

$ErrorActionPreference = 'Stop'
$repo = Split-Path -Parent (Split-Path -Parent $PSScriptRoot)
$timings = [ordered]@{}
$clock = [Diagnostics.Stopwatch]::StartNew()
$stage = [Diagnostics.Stopwatch]::StartNew()
$swapped = $false

function Write-Step([string] $Message) { Write-Host "==> $Message" -ForegroundColor Cyan }
function Complete-Stage([string] $Name) { $timings[$Name] = [math]::Round($stage.Elapsed.TotalSeconds, 1); $stage.Restart() }
function Write-Summary([string] $Outcome) {
    Write-Host ''
    Write-Host "Deploy $Tag to $Domain : $Outcome (total $([math]::Round($clock.Elapsed.TotalSeconds, 1)) s)"
    foreach ($k in $timings.Keys) { Write-Host ("  {0,-10} {1,6} s" -f $k, $timings[$k]) }
}
function Invoke-App { cloudron exec --app $App '--' @args }
function Stop-Deploy([string] $Message) {
    Write-Host "Deploy stopped: $Message" -ForegroundColor Red
    if ($swapped) {
        Write-Step 'Rolling back to the previous public folder'
        Invoke-App sh -c 'set -e; cd /app/data; mv public public.failed; mv public.previous public; rm -rf public.failed' | Out-Host
        Write-Host "Rolled back. https://$Domain/VERSION now serves: $(Get-LiveVersion)"
    }
    Write-Summary 'failed'
    exit 1
}
function Get-LiveVersion {
    try { (Invoke-WebRequest -UseBasicParsing -TimeoutSec 15 -Headers @{ 'Cache-Control' = 'no-cache' } "https://$Domain/VERSION?deploy=$([guid]::NewGuid())").Content.Trim() }
    catch { '' }
}

Push-Location $repo
try {
    Write-Step "Checking $Tag"
    git fetch --quiet --tags origin main
    if ($LASTEXITCODE) { Stop-Deploy 'git fetch failed' }
    $commit = git rev-parse --verify --quiet "$Tag^{commit}"
    if (-not $commit) { Stop-Deploy "tag $Tag does not exist" }
    git merge-base --is-ancestor $commit origin/main
    if ($LASTEXITCODE) { Stop-Deploy "$Tag is not on origin/main" }
    $release = gh release view $Tag --json isDraft --jq '.isDraft' 2>$null
    if ($release -ne 'false') { Stop-Deploy "$Tag has no published GitHub Release" }
    $expected = "$(git show "${Tag}:public/VERSION")".Trim()
    if ($expected.TrimStart('v') -ne $Tag.TrimStart('v')) { Stop-Deploy "public/VERSION at $Tag is $expected, not $Tag" }
    cloudron status --app $App | Out-Null
    if ($LASTEXITCODE) { Stop-Deploy "cloudron cannot reach app $App (run cloudron login)" }
    Write-Host "  $Tag = $($commit.Substring(0, 7)), live now: $(Get-LiveVersion)"
    Complete-Stage 'check'

    Write-Step 'Backing up the app'
    cloudron backup create --app $App | Out-Host
    if ($LASTEXITCODE) { Stop-Deploy 'cloudron backup create failed' }
    Complete-Stage 'backup'

    Write-Step 'Packing and uploading public/'
    $archive = Join-Path ([IO.Path]::GetTempPath()) "site-$Tag.tar.gz"
    git archive --format=tar.gz -o $archive $Tag public
    if ($LASTEXITCODE) { Stop-Deploy 'git archive failed' }
    Write-Host "  $([math]::Round((Get-Item $archive).Length / 1MB, 2)) MB"
    cloudron push --app $App $archive "/tmp/site-$Tag.tar.gz" | Out-Host
    if ($LASTEXITCODE) { Stop-Deploy 'cloudron push failed' }
    Remove-Item $archive
    Complete-Stage 'upload'

    Write-Step 'Unpacking and comparing with the live site'
    Invoke-App sh -c "set -e; cd /app/data; rm -rf public.next; mkdir public.next; tar -xzf /tmp/site-$Tag.tar.gz -C public.next --strip-components=1; rm -f /tmp/site-$Tag.tar.gz; chown -R www-data:www-data public.next" | Out-Host
    if ($LASTEXITCODE) { Stop-Deploy 'unpacking on the app failed' }
    $removed = @(Invoke-App sh -c 'cd /app/data/public && find . -type f | sort > /tmp/live.txt; cd /app/data/public.next && find . -type f | sort > /tmp/next.txt; comm -23 /tmp/live.txt /tmp/next.txt; rm -f /tmp/live.txt /tmp/next.txt' | Where-Object { $_ })
    if ($removed.Count -gt 0) {
        Write-Host "  $($removed.Count) live file(s) are not in ${Tag}:"
        $removed | Select-Object -First 20 | ForEach-Object { Write-Host "    $_" }
        if (-not $AllowRemovals) { Stop-Deploy 'the live site has files the tag does not. Add them to git, or rerun with -AllowRemovals' }
    }
    Complete-Stage 'unpack'

    Write-Step 'Swapping the site in'
    Invoke-App sh -c 'set -e; cd /app/data; rm -rf public.previous; mv public public.previous; mv public.next public' | Out-Host
    if ($LASTEXITCODE) { Stop-Deploy 'the swap failed' }
    $swapped = $true
    Complete-Stage 'swap'

    Write-Step "Checking https://$Domain/VERSION"
    $live = ''
    foreach ($attempt in 1..10) {
        $live = Get-LiveVersion
        if ($live -eq $expected) { break }
        Start-Sleep -Seconds 3
    }
    if ($live -ne $expected) { Stop-Deploy "https://$Domain/VERSION serves '$live', not '$expected'" }
    Complete-Stage 'verify'

    Write-Host "  https://$Domain/VERSION = $live. The previous site is kept in /app/data/public.previous for rollback."
    Write-Summary 'deployed'
} finally {
    Pop-Location
}
