<?php
declare(strict_types=1);

$publicRoot = realpath(__DIR__ . '/../public');

if ($publicRoot === false) {
    http_response_code(500);
    header('Content-Type: text/plain; charset=utf-8');
    echo 'Unable to resolve the public web root.';

    return true;
}

function apes_router_error_page(string $publicRoot, int $statusCode, string $fallbackMessage): void
{
    http_response_code($statusCode);

    $errorPagePath = match ($statusCode) {
        403 => $publicRoot . '/403.html',
        404 => $publicRoot . '/404.html',
        default => $publicRoot . '/500.html',
    };

    if (is_file($errorPagePath)) {
        header('Content-Type: text/html; charset=utf-8');
        readfile($errorPagePath);

        return;
    }

    header('Content-Type: text/plain; charset=utf-8');
    echo $fallbackMessage;
}

function apes_router_redirect(string $target, int $statusCode = 301): bool
{
    header('Location: ' . $target, true, $statusCode);

    return true;
}

function apes_router_content_type(string $path): string
{
    return match (strtolower((string) pathinfo($path, PATHINFO_EXTENSION))) {
        'css' => 'text/css; charset=utf-8',
        'js' => 'application/javascript; charset=utf-8',
        'json' => 'application/json; charset=utf-8',
        'xml' => 'application/xml; charset=utf-8',
        'txt' => 'text/plain; charset=utf-8',
        'svg' => 'image/svg+xml',
        'webp' => 'image/webp',
        'png' => 'image/png',
        'jpg', 'jpeg' => 'image/jpeg',
        'ico' => 'image/x-icon',
        'webmanifest' => 'application/manifest+json; charset=utf-8',
        default => 'text/html; charset=utf-8',
    };
}

function apes_router_serve_file(string $path): bool
{
    header('Content-Type: ' . apes_router_content_type($path));
    readfile($path);

    return true;
}

$requestUri = $_SERVER['REQUEST_URI'] ?? '/';
$requestParts = parse_url($requestUri);
$requestPath = $requestParts['path'] ?? '/';
$decodedPath = rawurldecode($requestPath);

$redirects = [
    '/index' => '/',
    '/missions/our-main-mission-statement/' => '/mission/our-main-mission-statement/',
    '/missions/our-main-mission-statement' => '/mission/our-main-mission-statement/',
    '/missions/support-ethical-rehabilitation/' => '/mission/support-ethical-rehabilitation/',
    '/missions/support-ethical-rehabilitation' => '/mission/support-ethical-rehabilitation/',
    '/changelog/' => '/change-log-hub/',
    '/changelog' => '/change-log-hub/',
    '/change-log/' => '/change-log-hub/',
    '/change-log' => '/change-log-hub/',
    '/messages' => '/messages/thank-you/',
    '/messages/' => '/messages/thank-you/',
    '/messages/volunteer-student-placement-interview-booking-links' => '/messages/thank-you/',
    '/messages/volunteer-student-placement-interview-booking-links/' => '/messages/thank-you/',
    '/news/post/Introducing-the-new-APES-CareBase/' => 'https://www.apesnews.org.uk/introducing-the-new-myapes-manage-your-details-online/',
    '/news/post/Introducing-the-new-APES-CareBase' => 'https://www.apesnews.org.uk/introducing-the-new-myapes-manage-your-details-online/',
    '/news/post/Urgent-APES-Must-Relocate-by-3-March-2026/' => 'https://www.apesnews.org.uk/tag/apes-cic/',
    '/news/post/Urgent-APES-Must-Relocate-by-3-March-2026' => 'https://www.apesnews.org.uk/tag/apes-cic/',
    '/news/post/APES-Partners-with-Double-the-Donation-to-Double-Your-Donation-Impact/' => 'https://www.apesnews.org.uk/tag/apes-donor-community/',
    '/news/post/APES-Partners-with-Double-the-Donation-to-Double-Your-Donation-Impact' => 'https://www.apesnews.org.uk/tag/apes-donor-community/',
    '/news/post/important-update-temporary-move-what-it-means-for-you/' => 'https://www.apesnews.org.uk/tag/apes-cic/',
    '/news/post/important-update-temporary-move-what-it-means-for-you' => 'https://www.apesnews.org.uk/tag/apes-cic/',
    '/news/post/fundraising-appeal-help-apes-invest-in-essential-welfare-management-software/' => 'https://www.apesnews.org.uk/tag/apes-donor-community/',
    '/news/post/fundraising-appeal-help-apes-invest-in-essential-welfare-management-software' => 'https://www.apesnews.org.uk/tag/apes-donor-community/',
    '/news/tag/moving-properties/' => 'https://www.apesnews.org.uk/tag/apes-cic/',
    '/news/tag/moving-properties' => 'https://www.apesnews.org.uk/tag/apes-cic/',
    '/news/tag/funds/' => 'https://www.apesnews.org.uk/tag/apes-donor-community/',
    '/news/tag/funds' => 'https://www.apesnews.org.uk/tag/apes-donor-community/',
];

if (isset($redirects[$decodedPath])) {
    return apes_router_redirect($redirects[$decodedPath]);
}

$forbiddenPrefixes = [
    '/includes',
    '/outputs',
    '/scripts',
];

foreach ($forbiddenPrefixes as $prefix) {
    if ($decodedPath === $prefix || str_starts_with($decodedPath, $prefix . '/')) {
        apes_router_error_page($publicRoot, 403, 'Forbidden');

        return true;
    }
}

if (str_starts_with($decodedPath, '/.')) {
    apes_router_error_page($publicRoot, 403, 'Forbidden');

    return true;
}

$relativePath = ltrim($decodedPath, '/');
$fileCandidate = realpath($publicRoot . '/' . $relativePath);

if ($fileCandidate !== false && str_starts_with($fileCandidate, $publicRoot) && is_file($fileCandidate)) {
    return apes_router_serve_file($fileCandidate);
}

$directoryCandidate = realpath($publicRoot . '/' . trim($relativePath, '/'));
if ($directoryCandidate !== false && str_starts_with($directoryCandidate, $publicRoot) && is_dir($directoryCandidate)) {
    if (!str_ends_with($decodedPath, '/')) {
        $queryString = isset($requestParts['query']) && $requestParts['query'] !== '' ? '?' . $requestParts['query'] : '';
        return apes_router_redirect($decodedPath . '/' . $queryString);
    }
}

$_SERVER['DOCUMENT_ROOT'] = $publicRoot;
$_SERVER['REQUEST_URI'] = $requestUri;

require $publicRoot . '/index.php';

return true;
