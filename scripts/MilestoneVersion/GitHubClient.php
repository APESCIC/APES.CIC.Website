<?php

namespace Scripts\MilestoneVersion;

use RuntimeException;

/**
 * GitHub REST client. With GITHUB_TOKEN or GH_TOKEN in the environment (as in CI) it calls the API directly;
 * otherwise it goes through the signed-in gh CLI. The token is never printed or stored.
 */
final class GitHubClient implements GitHub
{
    private const PER_PAGE = 100;

    public function __construct(
        private readonly string $repository,
        private readonly ?string $token,
        private readonly string $apiUrl = 'https://api.github.com',
    ) {}

    public static function fromEnvironment(string $workingDirectory): self
    {
        $repository = getenv('GITHUB_REPOSITORY') ?: self::repositoryFromRemote($workingDirectory);
        $token = getenv('GITHUB_TOKEN') ?: (getenv('GH_TOKEN') ?: null);

        return new self($repository, $token, rtrim(getenv('GITHUB_API_URL') ?: 'https://api.github.com', '/'));
    }

    public function openMilestones(): array
    {
        return array_map(
            fn (array $m): array => ['number' => (int) $m['number'], 'title' => (string) $m['title']],
            $this->paginate("repos/{$this->repository}/milestones?state=open"),
        );
    }

    public function tags(): array
    {
        return array_map(fn (array $t): string => (string) $t['name'], $this->paginate("repos/{$this->repository}/tags"));
    }

    public function issue(int $number): ?array
    {
        $issue = $this->get("repos/{$this->repository}/issues/{$number}");

        return $issue === null ? null : [
            'number' => (int) $issue['number'],
            'title' => (string) $issue['title'],
            'milestone' => $issue['milestone']['title'] ?? null,
        ];
    }

    public function pullRequest(int $number): ?array
    {
        $pr = $this->get("repos/{$this->repository}/pulls/{$number}");

        return $pr === null ? null : [
            'number' => (int) $pr['number'],
            'title' => (string) $pr['title'],
            'body' => (string) ($pr['body'] ?? ''),
            'milestone' => $pr['milestone']['title'] ?? null,
        ];
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function paginate(string $path): array
    {
        $items = [];
        $separator = str_contains($path, '?') ? '&' : '?';

        for ($page = 1; ; $page++) {
            $batch = $this->get($path.$separator.'per_page='.self::PER_PAGE.'&page='.$page) ?? [];
            array_push($items, ...$batch);
            if (count($batch) < self::PER_PAGE) {
                return $items;
            }
        }
    }

    /**
     * @return ?array<mixed> Decoded JSON, or null when GitHub answers 404.
     */
    private function get(string $path): ?array
    {
        [$status, $body] = $this->token === null ? $this->viaGh($path) : $this->viaHttp($path);

        if ($status === 404) {
            return null;
        }
        if ($status !== 200) {
            throw new RuntimeException(sprintf('GitHub API request for %s failed (HTTP %d).', strtok($path, '?'), $status));
        }

        $decoded = json_decode($body, true);
        if (! is_array($decoded)) {
            throw new RuntimeException(sprintf('GitHub API returned invalid JSON for %s.', strtok($path, '?')));
        }

        return $decoded;
    }

    /**
     * @return array{int, string}
     */
    private function viaHttp(string $path): array
    {
        $context = stream_context_create(['http' => [
            'method' => 'GET',
            'ignore_errors' => true,
            'timeout' => 30,
            'header' => implode("\r\n", [
                'Accept: application/vnd.github+json',
                'Authorization: Bearer '.$this->token,
                'User-Agent: apescic-milestone-version',
                'X-GitHub-Api-Version: 2022-11-28',
            ]),
        ]]);

        $body = @file_get_contents($this->apiUrl.'/'.$path, false, $context);
        $headers = $http_response_header ?? [];
        if ($body === false || $headers === [] || preg_match('#^HTTP/\S+\s+(\d{3})#', $headers[0], $m) !== 1) {
            throw new RuntimeException('Could not reach the GitHub API.');
        }

        return [(int) $m[1], $body];
    }

    /**
     * @return array{int, string}
     */
    private function viaGh(string $path): array
    {
        [$exitCode, $stdout, $stderr] = self::run(['gh', 'api', '-H', 'Accept: application/vnd.github+json', $path]);

        if ($exitCode === 0) {
            return [200, $stdout];
        }
        if (str_contains($stderr, 'HTTP 404') || str_contains($stderr, 'Not Found')) {
            return [404, ''];
        }
        if (str_contains($stderr, 'auth login') || str_contains($stderr, 'not logged')) {
            throw new RuntimeException('No GitHub credentials. Set GITHUB_TOKEN, or sign in with gh auth login.');
        }

        throw new RuntimeException('gh api failed: '.trim($stderr));
    }

    private static function repositoryFromRemote(string $workingDirectory): string
    {
        [$exitCode, $stdout] = self::run(['git', '-C', $workingDirectory, 'remote', 'get-url', 'origin']);

        if ($exitCode !== 0 || preg_match('#github\.com[:/]([^/]+/[^/]+?)(?:\.git)?/?$#', trim($stdout), $m) !== 1) {
            throw new RuntimeException('Could not work out the GitHub repository. Set GITHUB_REPOSITORY=owner/name.');
        }

        return $m[1];
    }

    /**
     * @param  list<string>  $command
     * @return array{int, string, string}
     */
    private static function run(array $command): array
    {
        $process = @proc_open($command, [1 => ['pipe', 'w'], 2 => ['pipe', 'w']], $pipes); // A fixed gh or git argument list, run without a shell.
        if (! is_resource($process)) {
            throw new RuntimeException(sprintf('Could not run %s. Is it installed and on PATH?', $command[0]));
        }

        $stdout = stream_get_contents($pipes[1]) ?: '';
        $stderr = stream_get_contents($pipes[2]) ?: '';
        fclose($pipes[1]);
        fclose($pipes[2]);

        return [proc_close($process), $stdout, $stderr];
    }
}
