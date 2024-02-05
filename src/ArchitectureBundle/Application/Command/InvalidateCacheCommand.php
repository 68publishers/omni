<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Application\Command;

use SixtyEightPublishers\ArchitectureBundle\Command\CommandInterface;

final class InvalidateCacheCommand implements CommandInterface
{
    /**
     * @param array<int, string> $keys
     * @param array<int, string> $tags
     */
    public function __construct(
        public readonly array $keys = [],
        public readonly array $tags = [],
    ) {}
}
