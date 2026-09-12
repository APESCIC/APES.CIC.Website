[CmdletBinding()]
param(
    [int] $Port = 8080,
    [string] $BindHost = "127.0.0.1"
)

Set-StrictMode -Version Latest
$ErrorActionPreference = "Stop"

. (Join-Path $PSScriptRoot "ApesPhpTools.ps1")

function Invoke-ApesHttpRequest {
    [CmdletBinding()]
    param(
        [Parameter(Mandatory = $true)]
        [string] $Uri,

        [switch] $NoRedirect
    )

    if ($NoRedirect) {
        $request = [System.Net.WebRequest]::Create($Uri)
        $request.AllowAutoRedirect = $false

        try {
            $response = $request.GetResponse()
        } catch [System.Net.WebException] {
            $response = $_.Exception.Response

            if ($null -eq $response) {
                throw
            }
        }

        $stream = $response.GetResponseStream()
        $reader = New-Object System.IO.StreamReader($stream)

        $statusCode = [int] $response.StatusCode
        $headers = $response.Headers

        try {
            $content = $reader.ReadToEnd()
        } finally {
            $reader.Dispose()
            $stream.Dispose()
            $response.Dispose()
        }

        return [pscustomobject]@{
            StatusCode = $statusCode
            Headers = $headers
            Content = $content
            Uri = $Uri
        }
    }

    try {
        $response = Invoke-WebRequest -Uri $Uri -UseBasicParsing

        return [pscustomobject]@{
            StatusCode = [int] $response.StatusCode
            Headers = $response.Headers
            Content = [string] $response.Content
            Uri = $Uri
        }
    } catch {
        $response = $_.Exception.Response

        if ($null -eq $response) {
            throw
        }

        $stream = $response.GetResponseStream()
        $reader = New-Object System.IO.StreamReader($stream)

        try {
            $content = $reader.ReadToEnd()
        } finally {
            $reader.Dispose()
            $stream.Dispose()
        }

        return [pscustomobject]@{
            StatusCode = [int] $response.StatusCode
            Headers = $response.Headers
            Content = $content
            Uri = $Uri
        }
    }
}

function Assert-ApesStatus {
    [CmdletBinding()]
    param(
        [Parameter(Mandatory = $true)]
        [pscustomobject] $Response,

        [Parameter(Mandatory = $true)]
        [int] $ExpectedStatusCode,

        [Parameter(Mandatory = $true)]
        [string] $Label
    )

    if ($Response.StatusCode -ne $ExpectedStatusCode) {
        throw "$Label expected HTTP $ExpectedStatusCode but received $($Response.StatusCode)."
    }
}

function Assert-ApesContains {
    [CmdletBinding()]
    param(
        [Parameter(Mandatory = $true)]
        [string] $Content,

        [Parameter(Mandatory = $true)]
        [string] $ExpectedText,

        [Parameter(Mandatory = $true)]
        [string] $Label
    )

    if ($Content -notlike "*$ExpectedText*") {
        throw "$Label did not contain expected text: $ExpectedText"
    }
}

function Get-ApesTrackedRouteSnapshots {
    [CmdletBinding()]
    param(
        [Parameter(Mandatory = $true)]
        [string] $PublicRoot
    )

    return @(Get-ChildItem -LiteralPath $PublicRoot -Recurse -File -Filter "index.html" |
        Where-Object {
            $relativePath = $_.FullName.Substring($PublicRoot.Length).TrimStart("\")
            $relativePath -ne "index.html"
        })
}

$repoRoot = (Resolve-Path (Join-Path $PSScriptRoot "..")).Path
$publicRoot = (Resolve-Path (Join-Path $repoRoot "public")).Path
$routerPath = (Resolve-Path (Join-Path $repoRoot "dev/router.php")).Path
$rootVersionPath = Join-Path $repoRoot "VERSION"
$publicVersionPath = Join-Path $publicRoot "VERSION"

$requiredFiles = @(
    "public/.htaccess",
    "public/index.php",
    "public/includes/site-data.php",
    "public/includes/render-page.php",
    "public/includes/header.php",
    "public/includes/footer.php",
    "public/theme/site.css",
    "public/theme/js/site.js",
    "public/403.html",
    "public/404.html",
    "public/500.html",
    "public/robots.txt",
    "public/sitemap.xml",
    "public/site.webmanifest",
    "public/CHANGELOG.md",
    "public/VERSION",
    "scripts/export-static-site.php",
    "scripts/ApesPhpTools.ps1",
    "scripts/export-static-site.ps1",
    "scripts/preview-php-source-site.ps1",
    "scripts/validate-public-site.ps1",
    "VERSION",
    "README.md",
    "CHANGELOG.md"
)

foreach ($relativePath in $requiredFiles) {
    $absolutePath = Join-Path $repoRoot $relativePath

    if (-not (Test-Path -LiteralPath $absolutePath -PathType Leaf)) {
        throw "Required file is missing: $relativePath"
    }
}

$obsoleteFiles = @(
    "public/index.html",
    "public/page.css",
    "public/page.js",
    "public/403.css",
    "public/403.js",
    "public/404.css",
    "public/404.js",
    "public/404.php",
    "public/500.css",
    "public/500.js",
    "public/assets/css/site.css",
    "public/assets/js/site.js",
    "scripts/build-static-page-assets.ps1",
    "scripts/preview-static-site.ps1",
    "scripts/static-preview-server.js"
)

foreach ($relativePath in $obsoleteFiles) {
    $absolutePath = Join-Path $repoRoot $relativePath

    if (Test-Path -LiteralPath $absolutePath) {
        throw "Obsolete static-first file still exists: $relativePath"
    }
}

$trackedRouteSnapshots = @(Get-ApesTrackedRouteSnapshots -PublicRoot $publicRoot)

if ($trackedRouteSnapshots.Count -gt 0) {
    $sample = ($trackedRouteSnapshots | Select-Object -First 5 | ForEach-Object {
        $_.FullName.Substring($publicRoot.Length).TrimStart("\").Replace("\", "/")
    }) -join ", "

    throw "Checked-in static route snapshots still exist under public/: $sample"
}

$rootVersion = (Get-Content -LiteralPath $rootVersionPath -Raw).Trim()
$publicVersion = (Get-Content -LiteralPath $publicVersionPath -Raw).Trim()

if ($rootVersion -eq "") {
    throw "Root VERSION is empty."
}

if ($publicVersion -eq "") {
    throw "public/VERSION is empty."
}

if ($rootVersion -ne $publicVersion) {
    throw "VERSION mismatch: root is '$rootVersion' but public/VERSION is '$publicVersion'."
}

$readme = Get-Content -LiteralPath (Join-Path $repoRoot "README.md") -Raw
foreach ($contentCheck in @(
    @{ Content = $readme; Label = "README"; Unexpected = "static-first HTML/CSS/JS runtime" },
    @{ Content = $readme; Label = "README"; Unexpected = "preview-static-site.ps1" },
    @{ Content = $readme; Label = "README"; Unexpected = "build-static-page-assets.ps1" }
)) {
    if ($contentCheck.Content -like "*$($contentCheck.Unexpected)*") {
        throw "$($contentCheck.Label) still contains outdated static-first text: $($contentCheck.Unexpected)"
    }
}

$phpPath = Get-ApesPhpPath
$preview = Start-ApesPhpPreview -PhpPath $phpPath -DocumentRoot $publicRoot -RouterPath $routerPath -BindHost $BindHost -Port $Port -WorkingDirectory $repoRoot

try {
    $baseUri = $preview.BaseUri
    $responses = @{}

    $okRoutes = @(
        "/",
        "/services/",
        "/donate/",
        "/contact/",
        "/search/",
        "/search/?q=volunteer",
        "/news/",
        "/policies/privacy/",
        "/mission/our-main-mission-statement/",
        "/change-log-hub/",
        "/messages/thank-you/",
        "/messages/thank-you/?application=volunteer",
        "/messages/thank-you/?application=student",
        "/24-7-services/",
        "/robots.txt",
        "/sitemap.xml",
        "/site.webmanifest",
        "/theme/site.css",
        "/theme/js/site.js"
    )

    foreach ($route in $okRoutes) {
        $response = Invoke-ApesHttpRequest -Uri ($baseUri + $route)
        Assert-ApesStatus -Response $response -ExpectedStatusCode 200 -Label $route
        $responses[$route] = $response
    }

    $indexRedirect = Invoke-ApesHttpRequest -Uri ($baseUri + "/index") -NoRedirect
    Assert-ApesStatus -Response $indexRedirect -ExpectedStatusCode 301 -Label "/index"
    if ($indexRedirect.Headers["Location"] -ne "/") {
        throw "/index redirect target should be '/' but was '$($indexRedirect.Headers["Location"])'."
    }

    $legacyNewsRoute = "/news/post/Urgent-APES-Must-Relocate-by-3-March-2026/"
    $legacyRedirect = Invoke-ApesHttpRequest -Uri ($baseUri + $legacyNewsRoute) -NoRedirect
    Assert-ApesStatus -Response $legacyRedirect -ExpectedStatusCode 301 -Label $legacyNewsRoute
    if ($legacyRedirect.Headers["Location"] -ne "https://www.apesnews.org.uk/tag/apes-cic/") {
        throw "$legacyNewsRoute did not redirect to the expected APES Newsroom URL."
    }

    $snapshotRoute = Invoke-ApesHttpRequest -Uri ($baseUri + "/services/index.html") -NoRedirect
    if ($snapshotRoute.StatusCode -eq 200) {
        throw "/services/index.html still resolves as a checked-in static snapshot route."
    }

    $missingRoute = Invoke-ApesHttpRequest -Uri ($baseUri + "/definitely-missing-route/")
    Assert-ApesStatus -Response $missingRoute -ExpectedStatusCode 404 -Label "missing route"
    Assert-ApesContains -Content $missingRoute.Content -ExpectedText "The page you requested could not be found." -Label "missing route"

    $forbiddenRoute = Invoke-ApesHttpRequest -Uri ($baseUri + "/includes/site-data.php")
    Assert-ApesStatus -Response $forbiddenRoute -ExpectedStatusCode 403 -Label "forbidden route"
    Assert-ApesContains -Content $forbiddenRoute.Content -ExpectedText "You do not have access to that page." -Label "forbidden route"

    if ($responses["/theme/site.css"].Headers["Content-Type"] -notlike "text/css*") {
        throw "/theme/site.css did not return a CSS content type."
    }

    if ($responses["/theme/js/site.js"].Headers["Content-Type"] -notlike "application/javascript*") {
        throw "/theme/js/site.js did not return a JavaScript content type."
    }

    foreach ($footerRoute in @("/", "/donate/", "/contact/", "/change-log-hub/")) {
        $content = $responses[$footerRoute].Content
        Assert-ApesContains -Content $content -ExpectedText "Privacy Policy" -Label "$footerRoute footer"
        Assert-ApesContains -Content $content -ExpectedText "Terms of Service" -Label "$footerRoute footer"
        Assert-ApesContains -Content $content -ExpectedText "Change Log Hub" -Label "$footerRoute footer"
        Assert-ApesContains -Content $content -ExpectedText "CIC No: 16253848" -Label "$footerRoute footer"
        Assert-ApesContains -Content $content -ExpectedText "APES CIC $rootVersion" -Label "$footerRoute version"
    }

    Assert-ApesContains -Content $responses["/search/"].Content -ExpectedText "Search the site to see results." -Label "/search/"
    Assert-ApesContains -Content $responses["/search/?q=volunteer"].Content -ExpectedText 'Search results for "volunteer"' -Label "/search/?q=volunteer"

    $thankYou = $responses["/messages/thank-you/"].Content
    Assert-ApesContains -Content $thankYou -ExpectedText "Thank you for your application" -Label "/messages/thank-you/"
    Assert-ApesContains -Content $thankYou -ExpectedText "https://outlook.office.com/book/APESBookings@apes.org.uk/s/ng1nE8bZkk6_ftWWQYXjQA2?ismsaljsauthenabled" -Label "/messages/thank-you/ student in person"
    Assert-ApesContains -Content $thankYou -ExpectedText "https://outlook.office.com/book/APESBookings@apes.org.uk/s/3iFA695k4UiUuZQj4x-RVQ2?ismsaljsauthenabled" -Label "/messages/thank-you/ student remote"
    Assert-ApesContains -Content $thankYou -ExpectedText "https://outlook.office.com/book/APESBookings@apes.org.uk/s/A68RwVNGOk2A4h2lquenDQ2?ismsaljsauthenabled" -Label "/messages/thank-you/ volunteer in person"
    Assert-ApesContains -Content $thankYou -ExpectedText "https://outlook.office.com/book/APESBookings@apes.org.uk/s/kCnebQbkBEycaLDScqNdfA2?ismsaljsauthenabled" -Label "/messages/thank-you/ volunteer remote"

    Assert-ApesContains -Content $responses["/messages/thank-you/?application=volunteer"].Content -ExpectedText "Book a volunteer interview in person" -Label "/messages/thank-you/?application=volunteer"
    Assert-ApesContains -Content $responses["/messages/thank-you/?application=student"].Content -ExpectedText "Book a student interview in person" -Label "/messages/thank-you/?application=student"

    $completionRedirect = Invoke-ApesHttpRequest -Uri ($baseUri + "/messages/volunteer-student-placement-interview-booking-links") -NoRedirect
    Assert-ApesStatus -Response $completionRedirect -ExpectedStatusCode 301 -Label "Sheltermanager completion redirect"
    if ($completionRedirect.Headers["Location"] -ne "/messages/thank-you/") {
        throw "Sheltermanager completion redirect target should be '/messages/thank-you/' but was '$($completionRedirect.Headers["Location"])'."
    }

    Write-Host "Validation passed:"
    Write-Host "- Confirmed PHP-first public structure and version alignment"
    Write-Host "- Checked representative HTTP routes, redirects, forbidden paths, and fallback errors"
    Write-Host "- Confirmed shared theme asset endpoints, footer-required links, and search rendering"
} finally {
    Stop-ApesPreviewProcess -Process $preview.Process
}
