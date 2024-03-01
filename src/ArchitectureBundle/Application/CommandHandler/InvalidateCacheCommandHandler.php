<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Application\CommandHandler;

use SixtyEightPublishers\ArchitectureBundle\Application\Command\InvalidateCacheCommand;
use SixtyEightPublishers\ArchitectureBundle\Command\CommandHandlerInterface;
use SixtyEightPublishers\ArchitectureBundle\Infrastructure\Cache\CacheRegistryInterface;
use function count;

final class InvalidateCacheCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly CacheRegistryInterface $cacheRegistry,
    ) {}

    public function __invoke(InvalidateCacheCommand $command): void
    {
        $cache = $this->cacheRegistry->getCache(
            name: $command->cacheName,
        );

        foreach ($command->keys as $key) {
            $cache->deleteItem(
                key: $key,
            );
        }

        if (0 < count($command->tags)) {
            $cache->clean(
                tags: $command->tags,
            );
        }
    }
}
