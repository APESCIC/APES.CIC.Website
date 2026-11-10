<?php

namespace Scripts\MilestoneVersion;

use Closure;
use RuntimeException;

final class Cli
{
    public const OK = 0;

    public const FAILED = 1;

    public const USAGE = 2;

    private const USAGE_TEXT = <<<'TXT'
Usage: php scripts/milestone-version <command> [arguments]

  next [--version-only]          Next milestone: the open milestone with the lowest version above the latest tag
  branch [--issue <n>]           Milestone branch name, or the issue branch name with --issue
  pr-title <issue>               PR title for an issue, using the issue's milestone version
  check-title "<title>"          Fail if the title uses a milestone number or the wrong version
      [--version vX.Y.Z | --issue <n> | --pr <n>]
  check-branch "<name>"          Fail if the branch uses a milestone number or the wrong version
  check-release <tag> "<title>"  Fail if the tag, release title (or --changelog "<heading>") disagree
      [--version vX.Y.Z] [--changelog "<heading>"]
  bump <vX.Y.Z>                  Set every version field in scripts/MilestoneVersion/fields.json, once per
      [--dry-run | --check]      milestone; the version must be the next milestone's (--offline skips that lookup)
      [--offline]

Examples: php scripts/milestone-version next, php scripts/milestone-version bump v1.3.0, php scripts/milestone-version check-title "[v1.3.0] Fix login (#42)"
Rule: .cursor/rules/version-shipping.mdc
TXT;

    /** @var resource */
    private $out;

    /** @var resource */
    private $err;

    private ?GitHub $client = null;

    /**
     * @param  Closure(): GitHub  $github  Created on first use, so checks that need no lookups work offline.
     * @param  resource|null  $out
     * @param  resource|null  $err
     */
    public function __construct(private readonly Closure $github, $out = null, $err = null, private readonly ?string $root = null)
    {
        $this->out = $out ?? STDOUT;
        $this->err = $err ?? STDERR;
    }

    /**
     * @param  list<string>  $argv  Arguments after the script name.
     */
    public function run(array $argv): int
    {
        [$positional, $options] = self::parseArguments($argv);
        $command = array_shift($positional);

        try {
            return match ($command) {
                'next' => $this->next($options),
                'branch' => $this->branch($options),
                'pr-title' => $this->prTitle($positional),
                'check-title' => $this->checkTitle($positional, $options),
                'check-branch' => $this->checkBranch($positional),
                'check-release' => $this->checkRelease($positional, $options),
                'bump' => $this->bump($positional, $options),
                null, 'help', '--help', '-h' => $this->usage($command === null ? self::USAGE : self::OK),
                default => $this->usage(self::USAGE, sprintf('Unknown command "%s".', $command)),
            };
        } catch (RuntimeException $e) {
            $this->error($e->getMessage());

            return self::USAGE;
        }
    }

    /**
     * @param  array<string, string|true>  $options
     */
    private function next(array $options): int
    {
        $next = $this->nextMilestone();
        if ($next === null) {
            return self::FAILED;
        }

        if (isset($options['version-only'])) {
            $this->line($next['version']->tag());

            return self::OK;
        }

        $this->line(sprintf('Next milestone:   %s', $next['title']));
        $this->line(sprintf('Version:          %s', $next['version']));
        $this->line(sprintf('Latest tag:       %s', $next['latestTag'] ?? 'none'));
        $this->line(sprintf('Milestone branch: %s', Milestones::branch($next['version'], $next['title'])));
        $this->line(sprintf('Milestone PR:     %s', Milestones::pullRequestTitle($next['version'], $next['title'])));
        $this->line(sprintf('Release title:    %s', Milestones::releaseTitle($next['version'], $next['title'])));
        $this->line('Issue branch:     <type>/<issue>-<slug>, for example chore/42-fix-login');
        $this->line(sprintf('PR title:         [%s] <summary> (#<issue>)', $next['version']));

        return self::OK;
    }

    /**
     * @param  array<string, string|true>  $options
     */
    private function branch(array $options): int
    {
        if (isset($options['issue'])) {
            $issue = $this->requireIssue((string) $options['issue']);
            $this->line(BranchRules::issueBranch(BranchRules::typeFor($issue['title']), $issue['number'], $issue['title']));

            return self::OK;
        }

        $next = $this->nextMilestone();
        if ($next === null) {
            return self::FAILED;
        }
        $this->line(Milestones::branch($next['version'], $next['title']));

        return self::OK;
    }

    /**
     * @param  list<string>  $positional
     */
    private function prTitle(array $positional): int
    {
        if (! isset($positional[0])) {
            return $this->usage(self::USAGE, 'pr-title needs an issue number.');
        }

        $issue = $this->requireIssue($positional[0]);
        $version = $this->milestoneVersion($issue['milestone'], sprintf('issue #%d', $issue['number']));
        if ($version === null) {
            return self::FAILED;
        }

        $this->line(TitleRules::prTitle($version, $issue['title'], $issue['number']));

        return self::OK;
    }

    /**
     * @param  list<string>  $positional
     * @param  array<string, string|true>  $options
     */
    private function checkTitle(array $positional, array $options): int
    {
        if (! isset($positional[0]) || trim($positional[0]) === '') {
            return $this->usage(self::USAGE, 'check-title needs a title.');
        }
        $title = $positional[0];

        [$expected, $source] = $this->expectedVersion($title, $options);
        if ($expected === null) {
            return self::FAILED;
        }

        $problems = TitleRules::check($title, $expected);
        if ($problems === []) {
            $this->line(sprintf('check-title: OK "%s" matches %s (%s).', $title, $expected, $source));

            return self::OK;
        }

        $this->fail('check-title', $title, $problems, sprintf('Expected (%s): %s', $source, TitleRules::suggestion($title, $expected)));

        return self::FAILED;
    }

    /**
     * @param  list<string>  $positional
     */
    private function checkBranch(array $positional): int
    {
        if (! isset($positional[0]) || trim($positional[0]) === '') {
            return $this->usage(self::USAGE, 'check-branch needs a branch name.');
        }
        $branch = $positional[0];

        $problems = BranchRules::check(
            $branch,
            fn (): array => array_values(array_filter(array_map(
                fn (array $m): ?SemVer => SemVer::fromMilestoneTitle($m['title']),
                $this->github()->openMilestones(),
            ))),
            function (int $number): ?SemVer {
                $issue = $this->github()->issue($number);

                return $issue === null || $issue['milestone'] === null ? null : SemVer::fromMilestoneTitle($issue['milestone']);
            },
        );

        if ($problems === []) {
            $this->line(sprintf('check-branch: OK "%s".', $branch));

            return self::OK;
        }

        $this->fail('check-branch', $branch, $problems);

        return self::FAILED;
    }

    /**
     * @param  list<string>  $positional
     * @param  array<string, string|true>  $options
     */
    private function checkRelease(array $positional, array $options): int
    {
        if (count($positional) < 2) {
            return $this->usage(self::USAGE, 'check-release needs a tag and a release title.');
        }
        [$tag, $title] = $positional;

        $releaseTitle = null;
        if (isset($options['version'])) {
            $expected = $this->parseVersionOption((string) $options['version']);
            $source = '--version';
        } else {
            $next = $this->nextMilestone();
            if ($next === null) {
                return self::FAILED;
            }
            [$expected, $source] = [$next['version'], 'next milestone "'.$next['title'].'"'];
            $releaseTitle = Milestones::releaseTitle($next['version'], $next['title']);
        }

        $problems = [];
        if ($releaseTitle !== null && trim($title) !== $releaseTitle) {
            $problems[] = sprintf('The release title should be "%s" (v<version> and the milestone name).', $releaseTitle);
        }
        $tagVersion = str_starts_with($tag, 'v') ? SemVer::parse($tag) : null;
        if ($tagVersion === null) {
            $problems[] = sprintf('Tag "%s" is not a version tag. Use vX.Y.Z, for example %s.', $tag, $expected);
        } elseif (! $tagVersion->equals($expected)) {
            $problems[] = sprintf('Tag %s does not match the milestone version %s.', $tag, $expected);
        }
        array_push($problems, ...TitleRules::check($title, $expected));
        if (isset($options['changelog'])) {
            $heading = (string) $options['changelog'];
            $plain = sprintf('%d.%d.%d', $expected->major, $expected->minor, $expected->patch);
            if (! str_contains($heading, $plain)) {
                $problems[] = sprintf('Changelog heading "%s" does not contain %s.', $heading, $plain);
            }
        }

        if ($problems === []) {
            $this->line(sprintf('check-release: OK tag %s and "%s" match %s (%s).', $tag, $title, $expected, $source));

            return self::OK;
        }

        $this->fail('check-release', $tag.' "'.$title.'"', $problems);

        return self::FAILED;
    }

    /**
     * @param  list<string>  $positional
     * @param  array<string, string|true>  $options
     */
    private function bump(array $positional, array $options): int
    {
        if (! isset($positional[0])) {
            return $this->usage(self::USAGE, 'bump needs a version, for example v1.3.0.');
        }
        $version = $this->parseVersionOption($positional[0]);
        $fields = VersionFields::load($this->root ?? (string) getcwd());

        if (isset($options['check'])) {
            $problems = $fields->mismatches($version);
            if ($problems === []) {
                $this->line(sprintf('bump --check: OK every version field holds %s.', $version));

                return self::OK;
            }
            $this->fail('bump --check', $version->tag(), $problems, sprintf('Run: php scripts/milestone-version bump %s', $version));

            return self::FAILED;
        }

        if (! isset($options['offline'])) {
            $next = $this->nextMilestone();
            if ($next === null) {
                return self::FAILED;
            }
            if (! $next['version']->equals($version)) {
                $this->error(sprintf('%s is not the next milestone\'s version. The next milestone is "%s" (%s). Stop and check before bumping.', $version, $next['title'], $next['version']));

                return self::FAILED;
            }
        }

        $dryRun = isset($options['dry-run']);
        foreach ($fields->bump($version, $dryRun) as $change) {
            $this->line(sprintf('%s %s: %s -> %s', $dryRun ? 'Would set' : 'Set', $change['file'], $change['from'] ?? '(new)', $change['to']));
        }
        foreach ($fields->notes() as $note) {
            $this->line('Next: '.$note);
        }

        return self::OK;
    }

    /**
     * @param  array<string, string|true>  $options
     * @return array{?SemVer, string}
     */
    private function expectedVersion(string $title, array $options): array
    {
        if (isset($options['version'])) {
            return [$this->parseVersionOption((string) $options['version']), '--version'];
        }

        $issueNumber = isset($options['issue']) ? (int) $options['issue'] : null;

        if ($issueNumber === null && isset($options['pr'])) {
            $pr = $this->github()->pullRequest((int) $options['pr']);
            if ($pr === null) {
                throw new RuntimeException(sprintf('Pull request #%s was not found.', $options['pr']));
            }
            if ($pr['milestone'] !== null) {
                return [$this->milestoneVersion($pr['milestone'], 'PR #'.$pr['number']), sprintf('milestone of PR #%d', $pr['number'])];
            }
            if (preg_match('/\b(?:close[sd]?|fix(?:e[sd])?|resolve[sd]?)\s+#(\d+)/i', $pr['body'], $m) === 1) {
                $issueNumber = (int) $m[1];
            }
        }

        if ($issueNumber === null && preg_match('/\(#(\d+)\)\s*$/', $title, $m) === 1) {
            $issueNumber = (int) $m[1];
        }

        if ($issueNumber !== null) {
            $issue = $this->requireIssue((string) $issueNumber);
            if ($issue['milestone'] !== null) {
                return [$this->milestoneVersion($issue['milestone'], 'issue #'.$issue['number']), sprintf('milestone of issue #%d', $issue['number'])];
            }
        }

        $next = $this->nextMilestone();

        return $next === null ? [null, ''] : [$next['version'], 'next milestone "'.$next['title'].'"'];
    }

    /**
     * @return ?array{number: int, title: string, version: SemVer, latestTag: ?SemVer}
     */
    private function nextMilestone(): ?array
    {
        $result = Milestones::next($this->github()->openMilestones(), $this->github()->tags());

        foreach ($result['warnings'] as $warning) {
            $this->warn($warning);
        }

        if ($result['next'] === null) {
            $this->error(sprintf(
                'No open milestone has a version above the latest tag %s. Milestone titles must start with vX.Y.Z.',
                $result['latestTag'] ?? '(none)',
            ));

            return null;
        }

        return $result['next'] + ['latestTag' => $result['latestTag']];
    }

    private function milestoneVersion(?string $milestone, string $owner): ?SemVer
    {
        if ($milestone === null) {
            $this->error(sprintf('%s has no milestone. Add it to its milestone first.', ucfirst($owner)));

            return null;
        }

        $version = SemVer::fromMilestoneTitle($milestone);
        if ($version === null) {
            $this->error(sprintf('The milestone of %s, "%s", does not start with a version (vX.Y.Z). Stop and ask for it to be retitled.', $owner, $milestone));
        }

        return $version;
    }

    /**
     * @return array{number: int, title: string, milestone: ?string}
     */
    private function requireIssue(string $number): array
    {
        if (! ctype_digit(ltrim($number, '#'))) {
            throw new RuntimeException(sprintf('"%s" is not an issue number.', $number));
        }

        $issue = $this->github()->issue((int) ltrim($number, '#'));
        if ($issue === null) {
            throw new RuntimeException(sprintf('Issue #%s was not found.', ltrim($number, '#')));
        }

        return $issue;
    }

    private function parseVersionOption(string $value): SemVer
    {
        return SemVer::parse($value) ?? throw new RuntimeException(sprintf('"%s" is not a version. Use vX.Y.Z.', $value));
    }

    private function github(): GitHub
    {
        return $this->client ??= ($this->github)();
    }

    /**
     * @param  list<string>  $problems
     */
    private function fail(string $check, string $subject, array $problems, ?string $hint = null): void
    {
        $this->error(sprintf('%s: FAIL "%s"', $check, $subject));
        foreach ($problems as $problem) {
            fwrite($this->err, '  - '.$problem.PHP_EOL);
            if (getenv('GITHUB_ACTIONS') === 'true') {
                fwrite($this->out, sprintf('::error title=%s::%s', $check, $problem).PHP_EOL);
            }
        }
        if ($hint !== null) {
            fwrite($this->err, '  '.$hint.PHP_EOL);
        }
    }

    private function usage(int $exitCode, ?string $message = null): int
    {
        if ($message !== null) {
            $this->error($message);
        }
        fwrite($exitCode === self::OK ? $this->out : $this->err, self::USAGE_TEXT.PHP_EOL);

        return $exitCode;
    }

    private function line(string $text): void
    {
        fwrite($this->out, $text.PHP_EOL);
    }

    private function warn(string $text): void
    {
        fwrite($this->err, 'Warning: '.$text.PHP_EOL);
    }

    private function error(string $text): void
    {
        fwrite($this->err, $text.PHP_EOL);
    }

    /**
     * @param  list<string>  $argv
     * @return array{list<string>, array<string, string|true>}
     */
    private static function parseArguments(array $argv): array
    {
        $positional = [];
        $options = [];
        $flags = ['version-only', 'dry-run', 'check', 'offline'];

        for ($i = 0; $i < count($argv); $i++) {
            $arg = $argv[$i];
            if ($arg === '--') {
                continue;
            }
            if (str_starts_with($arg, '--') && strlen($arg) > 2) {
                $name = substr($arg, 2);
                if (str_contains($name, '=')) {
                    [$name, $value] = explode('=', $name, 2);
                    $options[$name] = $value;
                } elseif (in_array($name, $flags, true) || ! isset($argv[$i + 1])) {
                    $options[$name] = true;
                } else {
                    $options[$name] = $argv[++$i];
                }

                continue;
            }
            $positional[] = $arg;
        }

        return [$positional, $options];
    }
}
