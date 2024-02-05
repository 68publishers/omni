<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Infrastructure\Cache;

interface CacheInterface
{
    /**
     * @throws UnableToReadCacheException
     */
    public function getItem(string $key): mixed;

    /**
     * @throws UnableToWriteCacheException
     */
    public function saveItem(CacheMetadata $metadata, mixed $item): void;

    /**
     * @throws UnableToDeleteCacheException
     */
    public function deleteItem(string $key): void;

    /**
     * @param array<int, string>|null $tags
     *
     * @throws UnableToDeleteCacheException
     */
    public function clean(?array $tags = null): void;
}
