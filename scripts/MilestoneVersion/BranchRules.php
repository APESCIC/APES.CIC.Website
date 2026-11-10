<?php

namespace Scripts\MilestoneVersion;

use Closure;

/**
 * Checks branch names against the milestone version rule.
 *
 *   milestone/v1-3-0-reporting                 milestone branch: the version of an open milestone
 *   chore/42-fix-login, cursor/fix/42-login    issue branch: any version in it must be the issue's milestone version
 */
final class BranchRules
{
    private const SKIPPED = '/^(main|master|dependencies|gh-pages|(dependabot|renovate)\/.+)$/';

    /**
     * @param  Closure(): list<SemVer>  $openVersions  Versions of the open milestones.
     * @param  Closure(int): ?SemVer  $issueVersion  Version of the issue's milestone, or null when it has none.
     * @return list<string> Problems found; empty when the branch name is valid.
     */
    public static function check(string $branch, Closure $openVersions, Closure $issueVersion): array
    {
        $branch = preg_replace('#^refs/heads/#', '', trim($branch)) ?? '';

        if ($branch === '') {
            return ['The branch name is empty.'];
        }
        if (preg_match(self::SKIPPED, $branch) === 1) {
            return [];
        }

        $problems = self::milestoneNumberProblems($branch);

        if (str_starts_with($branch, 'milestone/')) {
            return array_merge($problems, self::milestoneBranchProblems($branch, $openVersions));
        }

        if (preg_match('#^(?:cursor/)?[a-z]+/(\d+)-#', $branch, $m) === 1) {
            $version = self::versionIn($branch);
            if ($version !== null) {
                $expected = $issueVersion((int) $m[1]);
                if ($expected === null) {
                    $problems[] = sprintf('The branch carries %s, but issue #%s has no versioned milestone.', $version, $m[1]);
                } elseif (! $version->equals($expected)) {
                    $problems[] = sprintf('The branch carries %s, but issue #%s is in milestone %s. Use %s.', $version, $m[1], $expected, $expected->slug());
                }
            }
        }

        return $problems;
    }

    public static function issueBranch(string $type, int $issue, string $title): string
    {
        $slug = Milestones::slug(TitleRules::summary($title), 5);

        return sprintf('%s/%d%s', $type, $issue, $slug === '' ? '' : '-'.$slug);
    }

    public static function typeFor(string $issueTitle): string
    {
        return match (true) {
            preg_match('/\[Bug\]/i', $issueTitle) === 1 => 'fix',
            preg_match('/\[Chore\]/i', $issueTitle) === 1 => 'chore',
            preg_match('/\[Docs\]/i', $issueTitle) === 1 => 'docs',
            default => 'feature',
        };
    }

    private static function versionIn(string $branch): ?SemVer
    {
        if (preg_match('/(?:^|[\/-])v(\d+)-(\d+)-(\d+)(?=$|[\/-])/', $branch, $m) !== 1) {
            return null;
        }

        return new SemVer((int) $m[1], (int) $m[2], (int) $m[3]);
    }

    /**
     * @param  Closure(): list<SemVer>  $openVersions
     * @return list<string>
     */
    private static function milestoneBranchProblems(string $branch, Closure $openVersions): array
    {
        if (preg_match('#^milestone/v\d+-\d+-\d+(?:-[a-z0-9]+)*$#', $branch) !== 1) {
            return [sprintf('"%s" has no milestone version. Name milestone branches milestone/vX-Y-Z-<slug>, for example milestone/v1-3-0-reporting.', $branch)];
        }

        $version = self::versionIn($branch);
        foreach ($openVersions() as $open) {
            if ($version !== null && $version->equals($open)) {
                return [];
            }
        }

        return [sprintf('No open milestone has version %s. Check the version with php scripts/milestone-version next.', $version)];
    }

    /**
     * @return list<string>
     */
    private static function milestoneNumberProblems(string $branch): array
    {
        if (preg_match('#(?:^|[/-])(milestone-?\d+|m\d{1,3})(?=$|[/-])#i', $branch, $m) === 1
            || preg_match('#^milestone/(\d+)(?=$|-)#', $branch, $m) === 1) {
            return [sprintf('"%s" looks like a GitHub milestone number. Use the milestone version (vX-Y-Z) instead.', $m[1])];
        }

        return [];
    }
}
