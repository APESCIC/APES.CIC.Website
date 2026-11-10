<?php

namespace Scripts\MilestoneVersion;

/**
 * Checks PR, issue, changelog and release titles against the milestone version rule.
 *
 * Accepted forms, where the version must be the milestone's:
 *   [v1.3.0] Fix login (#42)        issue PRs into the milestone branch
 *   [v1.3.x] 07 Fix login           numbered issue titles (major and minor must match)
 *   [Epic] v1.3.0 Reporting         typed Epic or issue titles
 *   v1.3.0: Reporting               the milestone PR into main
 *   v1.3.0 Beta                     release and milestone titles
 */
final class TitleRules
{
    private const TYPES = 'Epic|Story|Task|Bug|Chore|Spike|Feature|Docs';

    private const VERSION = 'v\d+\.\d+\.\d+(?:-[0-9A-Za-z.-]+)?';

    /**
     * @return list<string> Problems found; empty when the title is valid.
     */
    public static function check(string $title, SemVer $expected): array
    {
        $title = trim($title);
        $problems = self::milestoneNumberProblems($title);

        if (preg_match('/\bv\d+\.\d+(?![.\d])/', $title, $m) === 1) {
            $problems[] = sprintf('"%s" is not a full version. Use all three parts, for example %s.', $m[0], $expected);
        }

        if (! self::hasVersionPrefix($title)) {
            $problems[] = sprintf('The title does not start with the milestone version. Start it with [%s].', $expected);
        }

        preg_match_all('/\bv(\d+)\.(\d+)\.(x|\d+(?:-[0-9A-Za-z.-]+)?)(?![\w.])/', $title, $matches, PREG_SET_ORDER);
        foreach ($matches as $match) {
            if ($match[3] === 'x') {
                if ((int) $match[1] !== $expected->major || (int) $match[2] !== $expected->minor) {
                    $problems[] = sprintf('"%s" does not match the milestone version %s.', $match[0], $expected);
                }

                continue;
            }
            $found = SemVer::parse($match[0]);
            if ($found === null || ! $found->equals($expected)) {
                $problems[] = sprintf('"%s" does not match the milestone version %s.', $match[0], $expected);
            }
        }

        if ($problems === [] && self::summary($title) === '') {
            $problems[] = 'The title has a version but no summary.';
        }

        return array_values(array_unique($problems));
    }

    public static function prTitle(SemVer $version, string $issueTitle, int $issueNumber): string
    {
        $summary = preg_replace('/\s*\(#\d+\)$/', '', self::summary($issueTitle)) ?? '';

        return sprintf('[%s] %s (#%d)', $version, $summary, $issueNumber);
    }

    /**
     * The title with its version, type, index and milestone-number prefixes removed.
     */
    public static function summary(string $title): string
    {
        $patterns = [
            '/^\[[^\]]*\]\s*/',
            '/^\d{2}\s+/',
            '/^'.self::VERSION.':?\s*/',
            '/^milestone\s*#?\s*\d+\s*[:\-]?\s*/i',
            '/^M\d+\s*[:\-]\s*/',
        ];

        $previous = null;
        $summary = trim($title);
        while ($previous !== $summary) {
            $previous = $summary;
            foreach ($patterns as $pattern) {
                $summary = trim(preg_replace($pattern, '', $summary) ?? '');
            }
        }

        return $summary;
    }

    public static function suggestion(string $title, SemVer $expected): string
    {
        $summary = self::summary($title);

        return sprintf('[%s] %s', $expected, $summary === '' ? '<summary>' : $summary);
    }

    /**
     * @return list<string>
     */
    private static function milestoneNumberProblems(string $title): array
    {
        $problems = [];

        if (preg_match('/\bmilestone\s*#?\s*\d+\b/i', $title, $m) === 1) {
            $problems[] = sprintf('"%s" is a GitHub milestone number. Refer to the milestone by its version instead.', $m[0]);
        }
        if (preg_match('#/milestones?/\d+\b#i', $title, $m) === 1) {
            $problems[] = sprintf('"%s" is a GitHub milestone number. Refer to the milestone by its version instead.', $m[0]);
        }
        if (preg_match('/\[#\d+\]/', $title, $m) === 1) {
            $problems[] = sprintf('"%s" looks like a GitHub milestone number. Use the milestone version in square brackets instead.', $m[0]);
        }
        if (preg_match('/(?<![\w#])M\d{1,3}\b/', $title, $m) === 1) {
            $problems[] = sprintf('"%s" looks like a GitHub milestone number. Refer to the milestone by its version instead.', $m[0]);
        }

        return $problems;
    }

    private static function hasVersionPrefix(string $title): bool
    {
        return preg_match('/^\['.self::VERSION.'\]\s+\S/', $title) === 1
            || preg_match('/^\[v\d+\.\d+\.x\]\s+\S/', $title) === 1
            || preg_match('/^\[(?:'.self::TYPES.')\]\s+'.self::VERSION.':?\s+\S/', $title) === 1
            || preg_match('/^'.self::VERSION.'(?::?\s+\S|$)/', $title) === 1;
    }
}
