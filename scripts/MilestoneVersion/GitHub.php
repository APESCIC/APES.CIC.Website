<?php

namespace Scripts\MilestoneVersion;

/**
 * Read-only view of the repository's milestones, tags, issues and pull requests.
 */
interface GitHub
{
    /**
     * @return list<array{number: int, title: string}>
     */
    public function openMilestones(): array;

    /**
     * @return list<string>
     */
    public function tags(): array;

    /**
     * @return ?array{number: int, title: string, milestone: ?string}
     */
    public function issue(int $number): ?array;

    /**
     * @return ?array{number: int, title: string, body: string, milestone: ?string}
     */
    public function pullRequest(int $number): ?array;
}
