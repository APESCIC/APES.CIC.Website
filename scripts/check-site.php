<?php

/*
 * Site checks run by CI and before every push: the VERSION files agree, every full HTML page under public/
 * declares a language and a title, the sitemap is valid XML, and the robots file and branded 404 page exist.
 * Needs only PHP.
 *
 * Usage: php scripts/check-site.php
 */

$root = dirname(__DIR__);
$public = $root.'/public';
$problems = [];

$version = trim((string) @file_get_contents($root.'/VERSION'));
$publicVersion = trim((string) @file_get_contents($public.'/VERSION'));
if ($version === '' || $version !== $publicVersion) {
    $problems[] = sprintf('VERSION (%s) and public/VERSION (%s) must match.', $version, $publicVersion);
}

foreach (['robots.txt', '404.html', 'sitemap.xml'] as $required) {
    if (! is_file($public.'/'.$required)) {
        $problems[] = "public/$required is missing.";
    }
}

if (is_file($public.'/sitemap.xml')) {
    libxml_use_internal_errors(true);
    if (simplexml_load_file($public.'/sitemap.xml') === false) {
        $problems[] = 'public/sitemap.xml is not valid XML: '.trim((string) (libxml_get_errors()[0]->message ?? 'unknown error'));
    }
}

$pages = 0;
$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($public, FilesystemIterator::SKIP_DOTS));
foreach ($files as $file) {
    if (! $file->isFile() || strtolower($file->getExtension()) !== 'html') {
        continue;
    }
    $html = (string) file_get_contents($file->getPathname());
    if (! preg_match('/<html[\s>]/i', $html)) {
        continue;
    }
    $pages++;
    $relative = 'public/'.ltrim(str_replace('\\', '/', substr($file->getPathname(), strlen($public))), '/');
    if (! preg_match('/<html[^>]*\slang=["\'][a-z]{2}/i', $html)) {
        $problems[] = "$relative: the <html> element has no lang attribute.";
    }
    if (! preg_match('/<title>\s*\S[^<]*<\/title>/i', $html)) {
        $problems[] = "$relative: no <title>.";
    }
}

if ($problems !== []) {
    fwrite(STDERR, "Site checks failed:\n - ".implode("\n - ", $problems)."\n");
    exit(1);
}

printf("Site checks passed: VERSION %s, %d pages.\n", $version, $pages);
