<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Application\CommandHandler;

use SixtyEightPublishers\ArchitectureBundle\Application\Command\InvalidateCacheCommand;
use SixtyEightPublishers\ArchitectureBundle\Command\CommandHandlerInterface;
use SixtyEightPublishers\ArchitectureBundle\Infrastructure\Cache\CacheInterface;
use function count;

final class InvalidateCacheCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly CacheInterface $cache,
    ) {}

    public function __invoke(InvalidateCacheCommand $command): void
    {
        foreach ($command->keys as $key) {
            $this->cache->deleteItem(
                key: $key,
            );
        }

        if (0 < count($command->tags)) {
            $this->cache->clean(
                tags: $command->tags,
            );
        }
    }
}
