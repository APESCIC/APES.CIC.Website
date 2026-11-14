<?php

/*
 * Laravel Herd driver for the local preview. Herd serves sites with nginx, which ignores public/.htaccess,
 * so this driver reads that file on every request and applies its forbidden-path [F] and redirect [R=...]
 * rules. public/.htaccess stays the only place the rules live; Cloudron's Apache applies the same file in
 * production. This file sits outside public/, so it is never deployed.
 *
 * Supported: RewriteCond on %{HTTP_HOST} (with [NC]), $1-style back-references, %{REQUEST_URI} in the
 * target, and Apache's query-string handling (kept unless the target has its own, merged with [QSA]).
 * The security headers reach PHP responses only: nginx drops them from the static files Herd hands back.
 */

use Valet\Drivers\BasicWithPublicValetDriver;

class LocalValetDriver extends BasicWithPublicValetDriver
{
    public function serves(string $sitePath, string $siteName, string $uri): bool
    {
        return is_file($sitePath.'/public/.htaccess');
    }

    public function isStaticFile(string $sitePath, string $siteName, string $uri)/*: string|false */
    {
        if ($this->matchingRule($sitePath, $uri) !== null) {
            return false;
        }

        return parent::isStaticFile($sitePath, $siteName, $uri);
    }

    public function frontControllerPath(string $sitePath, string $siteName, string $uri): ?string
    {
        $this->sendHeaders();

        $rule = $this->matchingRule($sitePath, $uri);

        if ($rule !== null && $rule['forbidden']) {
            http_response_code(403);
            header('Content-Type: text/html; charset=utf-8');
            readfile($sitePath.'/public/403.html');
            exit;
        }

        if ($rule !== null) {
            http_response_code($rule['status']);
            header('Location: '.$this->redirectLocation($rule));
            exit;
        }

        return parent::frontControllerPath($sitePath, $siteName, $uri);
    }

    /**
     * The first [F] or [R] rule in public/.htaccess that matches the request, or null.
     *
     * @return array{forbidden: bool, status: int, target: string, qsa: bool}|null
     */
    private function matchingRule(string $sitePath, string $uri): ?array
    {
        $path = ltrim($uri, '/');
        $host = (string) ($_SERVER['HTTP_HOST'] ?? '');
        $conditionsHold = true;

        foreach (file($sitePath.'/public/.htaccess', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
            $line = trim($line);

            if (preg_match('/^RewriteCond\s+(\S+)\s+(\S+)(?:\s+\[([^\]]*)\])?$/i', $line, $cond)) {
                $flags = strtoupper($cond[3] ?? '');
                $conditionsHold = $conditionsHold && $cond[1] === '%{HTTP_HOST}'
                    && preg_match($this->regex($cond[2], str_contains($flags, 'NC')), $host) === 1;

                continue;
            }

            if (! preg_match('/^RewriteRule\s+(\S+)\s+(\S+)(?:\s+\[([^\]]*)\])?$/i', $line, $rule)) {
                continue;
            }

            $applies = $conditionsHold;
            $conditionsHold = true;
            $flags = array_map('trim', explode(',', strtoupper($rule[3] ?? '')));
            $redirect = preg_grep('/^R(=\d{3})?$/', $flags);
            $forbidden = in_array('F', $flags, true);

            if (! $applies || (! $forbidden && $redirect === [])) {
                continue;
            }

            if (! preg_match($this->regex($rule[1], in_array('NC', $flags, true)), $path, $matches)) {
                continue;
            }

            $target = preg_replace_callback('/\$(\d)/', fn ($m) => $matches[(int) $m[1]] ?? '', $rule[2]);

            return [
                'forbidden' => $forbidden,
                'status' => $redirect === [] ? 302 : (int) (substr((string) reset($redirect), 2) ?: 302),
                'target' => str_replace('%{REQUEST_URI}', strtok((string) $_SERVER['REQUEST_URI'], '?'), $target),
                'qsa' => in_array('QSA', $flags, true),
            ];
        }

        return null;
    }

    /**
     * @param  array{target: string, qsa: bool}  $rule
     */
    private function redirectLocation(array $rule): string
    {
        $target = $rule['target'];
        $query = (string) ($_SERVER['QUERY_STRING'] ?? '');

        if ($query !== '' && ! str_contains($target, '?')) {
            $target .= '?'.$query;
        } elseif ($query !== '' && $rule['qsa']) {
            $target .= '&'.$query;
        }

        if (preg_match('#^https?://#i', $target)) {
            return $target;
        }

        return 'http://'.$_SERVER['HTTP_HOST'].'/'.ltrim($target, '/');
    }

    private function sendHeaders(): void
    {
        header('X-Content-Type-Options: nosniff');
        header('Referrer-Policy: strict-origin-when-cross-origin');
    }

    private function regex(string $pattern, bool $caseInsensitive): string
    {
        return '#'.str_replace('#', '\#', $pattern).'#'.($caseInsensitive ? 'i' : '');
    }
}
