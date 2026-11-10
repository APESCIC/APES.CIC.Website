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

    $laragonPhp = Get-ChildItem -LiteralPath "C:\laragon\bin\php" -Directory -ErrorAction SilentlyContinue |
        Where-Object { $_.Name -match '^php-8\.4\.\d+(-\d+)?-nts-Win32' } |
        Sort-Object Name -Descending |
        Select-Object -First 1
    if ($null -ne $laragonPhp) {
        $candidates.Add((Join-Path $laragonPhp.FullName "php.exe"))
    }

    foreach ($candidate in $candidates | Select-Object -Unique) {
        if (Test-Path -LiteralPath $candidate -PathType Leaf) {
            return (Resolve-Path -LiteralPath $candidate).Path
        }
    }

    throw "Unable to locate php.exe. Add PHP to PATH, set APES_PHP, or install Laragon's PHP 8.4 (see docs/laragon/profile.md)."
}
