<?php

namespace Scripts\MilestoneVersion;

/**
 * A semantic version (vMAJOR.MINOR.PATCH with an optional pre-release suffix),
 * compared by the rules at https://semver.org/#spec-item-11.
 */
final class SemVer
{
    private const PATTERN = '/^v?(0|[1-9]\d*)\.(0|[1-9]\d*)\.(0|[1-9]\d*)(?:-([0-9A-Za-z-]+(?:\.[0-9A-Za-z-]+)*))?(?:\+[0-9A-Za-z.-]+)?$/';

    /**
     * @param  list<string>  $preRelease
     */
    public function __construct(
        public readonly int $major,
        public readonly int $minor,
        public readonly int $patch,
        public readonly array $preRelease = [],
    ) {}

    public static function parse(string $value): ?self
    {
        if (preg_match(self::PATTERN, trim($value), $m) !== 1) {
            return null;
        }

        $preRelease = isset($m[4]) && $m[4] !== '' ? explode('.', $m[4]) : [];

        return new self((int) $m[1], (int) $m[2], (int) $m[3], $preRelease);
    }

    /**
     * Reads the version from the start of a milestone title, e.g. "v0.58.3 Dev: Laragon local development".
     */
    public static function fromMilestoneTitle(string $title): ?self
    {
        if (preg_match('/^(v\d+\.\d+\.\d+(?:-[0-9A-Za-z.-]+)?)(?=$|[\s:])/', trim($title), $m) !== 1) {
            return null;
        }

        return self::parse($m[1]);
    }

    public static function compare(self $a, self $b): int
    {
        foreach (['major', 'minor', 'patch'] as $part) {
            if ($a->{$part} !== $b->{$part}) {
                return $a->{$part} <=> $b->{$part};
            }
        }

        if ($a->preRelease === [] || $b->preRelease === []) {
            return count($b->preRelease) <=> count($a->preRelease);
        }

        $length = max(count($a->preRelease), count($b->preRelease));
        for ($i = 0; $i < $length; $i++) {
            if (! isset($a->preRelease[$i])) {
                return -1;
            }
            if (! isset($b->preRelease[$i])) {
                return 1;
            }

            $result = self::compareIdentifier($a->preRelease[$i], $b->preRelease[$i]);
            if ($result !== 0) {
                return $result;
            }
        }

        return 0;
    }

    public function equals(self $other): bool
    {
        return self::compare($this, $other) === 0;
    }

    public function sameMinor(self $other): bool
    {
        return $this->major === $other->major && $this->minor === $other->minor;
    }

    public function tag(): string
    {
        $tag = sprintf('v%d.%d.%d', $this->major, $this->minor, $this->patch);

        return $this->preRelease === [] ? $tag : $tag.'-'.implode('.', $this->preRelease);
    }

    /**
     * The version as used in branch names: v0.58.3 becomes v0-58-3.
     */
    public function slug(): string
    {
        return str_replace('.', '-', $this->tag());
    }

    public function __toString(): string
    {
        return $this->tag();
    }

    private static function compareIdentifier(string $a, string $b): int
    {
        $aNumeric = ctype_digit($a);
        $bNumeric = ctype_digit($b);

        if ($aNumeric && $bNumeric) {
            return (int) $a <=> (int) $b;
        }
        if ($aNumeric !== $bNumeric) {
            return $aNumeric ? -1 : 1;
        }

        return strcmp($a, $b) <=> 0;
    }
}
