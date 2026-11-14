Set-StrictMode -Version Latest
$ErrorActionPreference = "Stop"

function Get-ApesPhpPath {
    [CmdletBinding()]
    param(
        [string[]] $AdditionalCandidates = @()
    )

    $candidates = [System.Collections.Generic.List[string]]::new()

    if ($env:APES_PHP) {
        $candidates.Add($env:APES_PHP)
    }

    foreach ($commandName in @("php", "php.exe", "php8.exe", "php8.2.exe")) {
        $command = Get-Command $commandName -ErrorAction SilentlyContinue | Select-Object -First 1

        if ($null -ne $command -and $command.Source) {
            $candidates.Add($command.Source)
        }
    }

    foreach ($candidate in $AdditionalCandidates) {
        if ($candidate) {
            $candidates.Add($candidate)
        }
    }

    $candidates.Add((Join-Path $env:USERPROFILE ".config\herd\bin\php84\php.exe"))

    foreach ($candidate in $candidates | Select-Object -Unique) {
        if (Test-Path -LiteralPath $candidate -PathType Leaf) {
            return (Resolve-Path -LiteralPath $candidate).Path
        }
    }

    throw "Unable to locate php.exe. Add PHP to PATH, set APES_PHP, or install Laravel Herd's PHP 8.4 (see docs/herd/profile.md)."
}
