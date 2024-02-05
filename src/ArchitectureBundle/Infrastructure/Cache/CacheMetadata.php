<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Infrastructure\Cache;

use DateTimeImmutable;

final class CacheMetadata
{
    /**
     * @param array<int, string> $tags
     */
    public function __construct(
        public readonly string $key,
        public readonly int|DateTimeImmutable|null $expiration,
        public readonly array $tags = [],
    ) {}
}
