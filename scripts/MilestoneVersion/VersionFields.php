<?php

namespace Scripts\MilestoneVersion;

use RuntimeException;

/**
 * Sets every version field listed in scripts/MilestoneVersion/fields.json to the milestone version, so the
 * fields are never edited by hand. Each field is one of:
 *
 *   {"file": "VERSION", "type": "plain"}                                  the whole file is the version
 *   {"file": "CloudronManifest.json", "type": "json", "key": "version"}   the first "key": "..." value
 *   {"file": "config/app.php", "type": "regex", "pattern": "..."}         capture group 1 of the pattern
 *   {"type": "replace-all", "dir": "public", "pattern": "*.html",        every copy of the old version, for
 *    "exclude": ["public/changelog/index.html"]}                         version text repeated in static pages
 *
 * replace-all swaps the version the first plain field held before the bump, written in the field's format
 * (so a "v3.2.2b" or "3.2.3" VERSION finds "v3.2.2b" or "v3.2.3" in a tag-format page). Exclude pages that
 * list past versions, such as a change log, so their history is never rewritten.
 *
 * "format" is "plain" (1.3.0, the default) or "tag" (v1.3.0). An optional "releaseNote" copies a template
 * to a new file for the version when none exists yet.
 */
final class VersionFields
{
    /**
     * @param  array{fields?: list<array<string, string>>, releaseNote?: array{template: string, path: string}, notes?: list<string>}  $config
     */
    public function __construct(private readonly string $root, private readonly array $config) {}

    public static function load(string $root): self
    {
        $file = $root.'/scripts/MilestoneVersion/fields.json';
        if (! is_file($file)) {
            throw new RuntimeException('scripts/MilestoneVersion/fields.json is missing. It lists the version fields to set.');
        }

        $config = json_decode((string) file_get_contents($file), true);
        if (! is_array($config)) {
            throw new RuntimeException('scripts/MilestoneVersion/fields.json is not valid JSON.');
        }

        return new self($root, $config);
    }

    /**
     * @return list<array{file: string, from: ?string, to: string}>
     */
    public function bump(SemVer $version, bool $dryRun = false): array
    {
        $changes = [];
        $previous = $this->previousVersion();
        foreach ($this->config['fields'] ?? [] as $field) {
            if (($field['type'] ?? 'plain') === 'replace-all') {
                array_push($changes, ...$this->replaceAll($field, $previous, self::format($version, $field['format'] ?? 'plain'), $dryRun));

                continue;
            }
            $path = $this->path($field['file']);
            $value = self::format($version, $field['format'] ?? 'plain');
            $contents = (string) file_get_contents($path);
            [$from, $updated] = $this->replace($field, $contents, $value);
            if ($updated !== $contents && ! $dryRun) {
                file_put_contents($path, $updated);
            }
            $changes[] = ['file' => $field['file'], 'from' => $from, 'to' => $value];
        }

        if (isset($this->config['releaseNote'])) {
            $note = $this->config['releaseNote'];
            $target = str_replace(['{tag}', '{plain}'], [$version->tag(), self::format($version, 'plain')], $note['path']);
            $targetPath = $this->root.'/'.$target;
            if (! is_file($targetPath)) {
                $template = (string) file_get_contents($this->path($note['template']));
                $contents = preg_replace('/"version"\s*:\s*"[^"]*"/', '"version": "'.$version->tag().'"', $template, 1) ?? $template;
                if (! $dryRun) {
                    file_put_contents($targetPath, $contents);
                }
                $changes[] = ['file' => $target, 'from' => null, 'to' => 'new release note from '.$note['template']];
            }
        }

        return $changes;
    }

    /**
     * @return list<string> Fields that do not hold the version.
     */
    public function mismatches(SemVer $version): array
    {
        $problems = [];
        foreach ($this->config['fields'] ?? [] as $field) {
            if (($field['type'] ?? 'plain') === 'replace-all') {
                continue;
            }
            $value = self::format($version, $field['format'] ?? 'plain');
            [$from] = $this->replace($field, (string) file_get_contents($this->path($field['file'])), $value);
            if ($from !== $value) {
                $problems[] = sprintf('%s holds "%s", not %s.', $field['file'], $from ?? '', $value);
            }
        }

        return $problems;
    }

    /**
     * @return list<string>
     */
    public function notes(): array
    {
        return $this->config['notes'] ?? [];
    }

    /**
     * @param  array<string, string>  $field
     * @return array{?string, string}
     */
    private function replace(array $field, string $contents, string $value): array
    {
        $type = $field['type'] ?? 'plain';
        $pattern = match ($type) {
            'plain' => null,
            'json' => '/"'.preg_quote($field['key'], '/').'"\s*:\s*"([^"]*)"/',
            'regex' => '/'.$field['pattern'].'/',
            default => throw new RuntimeException(sprintf('Unknown field type "%s" for %s.', $type, $field['file'])),
        };

        if ($pattern === null) {
            return [trim($contents), $value."\n"];
        }

        if (preg_match($pattern, $contents, $match, PREG_OFFSET_CAPTURE) !== 1 || ! isset($match[1])) {
            throw new RuntimeException(sprintf('%s has no version value matching fields.json.', $field['file']));
        }
        [$from, $offset] = $match[1];

        return [$from, substr_replace($contents, $value, $offset, strlen($from))];
    }

    /**
     * The value the first plain field (normally VERSION) holds before the bump.
     */
    private function previousVersion(): ?string
    {
        foreach ($this->config['fields'] ?? [] as $field) {
            if (($field['type'] ?? 'plain') === 'plain') {
                return trim((string) file_get_contents($this->path($field['file'])));
            }
        }

        return null;
    }

    /**
     * Replaces every copy of the previous version in the files under "dir" whose names match "pattern",
     * for version text repeated in static pages (footers and the like).
     *
     * @param  array<string, string>  $field
     * @return list<array{file: string, from: ?string, to: string}>
     */
    private function replaceAll(array $field, ?string $previous, string $value, bool $dryRun): array
    {
        if ($previous === null || ltrim($previous, 'v') === '') {
            return [];
        }
        $needle = ($field['format'] ?? 'plain') === 'tag' ? 'v'.ltrim($previous, 'v') : ltrim($previous, 'v');
        if ($needle === $value) {
            return [];
        }

        $changes = [];
        $exclude = (array) ($field['exclude'] ?? []);
        $dir = $this->root.'/'.trim($field['dir'], '/');
        $files = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dir, \FilesystemIterator::SKIP_DOTS));
        foreach ($files as $file) {
            if (! $file->isFile() || ! fnmatch($field['pattern'], $file->getFilename())) {
                continue;
            }
            $relative = ltrim(str_replace('\\', '/', substr($file->getPathname(), strlen($this->root))), '/');
            $contents = (string) file_get_contents($file->getPathname());
            if (in_array($relative, $exclude, true) || ! str_contains($contents, $needle)) {
                continue;
            }
            if (! $dryRun) {
                file_put_contents($file->getPathname(), str_replace($needle, $value, $contents));
            }
            $changes[] = ['file' => $relative, 'from' => $needle, 'to' => $value];
        }
        sort($changes);

        return $changes;
    }

    private function path(string $file): string
    {
        $path = $this->root.'/'.$file;
        if (! is_file($path)) {
            throw new RuntimeException(sprintf('%s, listed in fields.json, does not exist.', $file));
        }

        return $path;
    }

    private static function format(SemVer $version, string $format): string
    {
        return $format === 'tag' ? $version->tag() : ltrim($version->tag(), 'v');
    }
}
