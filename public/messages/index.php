<?php
declare(strict_types=1);

$queryString = $_SERVER['QUERY_STRING'] ?? '';
$target = '/messages/vol-stu-application-thank-you/' . ($queryString !== '' ? '?' . $queryString : '');

header('Location: ' . $target, true, 301);
exit;
