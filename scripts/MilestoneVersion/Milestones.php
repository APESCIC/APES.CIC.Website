<?php

namespace Scripts\MilestoneVersion;

/**
 * Picks the next milestone: the open milestone with the lowest version above the latest release tag.
 */
final class Milestones
{
    private const STOP_WORDS = ['a', 'an', 'and', 'by', 'for', 'in', 'of', 'on', 'or', 'the', 'to', 'via', 'with'];

    /**
     * @param  list<array{number: int, title: string}>  $openMilestones
     * @param  list<string>  $tags
     * @return array{next: ?array{number: int, title: string, version: SemVer}, latestTag: ?SemVer, warnings: list<string>}
     */
    public static function next(array $openMilestones, array $tags): array
    {
        $latestTag = self::latest($tags);
        $warnings = [];
        $candidates = [];

        foreach ($openMilestones as $milestone) {
            $version = SemVer::fromMilestoneTitle($milestone['title']);
            if ($version === null) {
                $warnings[] = sprintf('Ignored open milestone "%s": its title does not start with a version (vX.Y.Z).', $milestone['title']);

                continue;
            }
            if ($latestTag !== null && SemVer::compare($version, $latestTag) <= 0) {
                $warnings[] = sprintf('Open milestone "%s" is at or below the latest tag %s. Close it or retitle it.', $milestone['title'], $latestTag);

                continue;
            }
            $candidates[] = $milestone + ['version' => $version];
        }

        usort($candidates, fn (array $a, array $b): int => SemVer::compare($a['version'], $b['version']));

        return ['next' => $candidates[0] ?? null, 'latestTag' => $latestTag, 'warnings' => $warnings];
    }

    /**
     * @param  list<string>  $tags
     */
    public static function latest(array $tags): ?SemVer
    {
        $latest = null;
        foreach ($tags as $tag) {
            if (! str_starts_with($tag, 'v')) {
                continue;
            }
            $version = SemVer::parse($tag);
            if ($version !== null && ($latest === null || SemVer::compare($version, $latest) > 0)) {
                $latest = $version;
            }
        }

        return $latest;
    }

    /**
     * The milestone name without its version or label, e.g. "v1.31.0 Beta: SEO and social previews" gives "SEO and social previews".
     */
    public static function name(string $title): string
    {
        $name = preg_replace('/^v\d+\.\d+\.\d+(?:-[0-9A-Za-z.-]+)?:?\s*/', '', trim($title)) ?? '';

        return trim(preg_replace('/^[A-Za-z]+:\s+/', '', $name) ?? '');
    }

    /**
     * "v1.3.0 Beta: Public UX & compliance" gives "v1.3.0 Public UX & compliance".
     */
    public static function releaseTitle(SemVer $version, string $title): string
    {
        $name = self::name($title);

        return $name === '' ? $version->tag() : $version->tag().' '.$name;
    }

    /**
     * "v1.3.0 Beta: Public UX & compliance" gives "v1.3.0: Public UX & compliance".
     */
    public static function pullRequestTitle(SemVer $version, string $title): string
    {
        $name = self::name($title);

        return $version->tag().': '.($name === '' ? '<summary>' : $name);
    }

    public static function branch(SemVer $version, string $title): string
    {
        $slug = self::slug(self::name($title));

        return 'milestone/'.$version->slug().($slug === '' ? '' : '-'.$slug);
    }

    public static function slug(string $text, int $maxWords = 4): string
    {
        $words = preg_split('/[^a-z0-9]+/', strtolower($text), -1, PREG_SPLIT_NO_EMPTY) ?: [];
        $words = array_values(array_filter($words, fn (string $word): bool => ! in_array($word, self::STOP_WORDS, true)));

        return implode('-', array_slice($words, 0, $maxWords));
    }
}
