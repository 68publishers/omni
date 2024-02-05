<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Infrastructure\NetteCache;

use DateTimeImmutable;
use Nette\Caching\Cache;
use Nette\Caching\Storage;
use SixtyEightPublishers\ArchitectureBundle\Infrastructure\Cache\CacheInterface;
use SixtyEightPublishers\ArchitectureBundle\Infrastructure\Cache\CacheMetadata;
use SixtyEightPublishers\ArchitectureBundle\Infrastructure\Cache\UnableToDeleteCacheException;
use SixtyEightPublishers\ArchitectureBundle\Infrastructure\Cache\UnableToReadCacheException;
use SixtyEightPublishers\ArchitectureBundle\Infrastructure\Cache\UnableToWriteCacheException;
use Throwable;
use function count;
use function is_int;

final class NetteCache implements CacheInterface
{
    private readonly Cache $cache;

    public function __construct(
        Storage $storage,
    ) {
        $this->cache = new Cache(
            storage: $storage,
            namespace: self::class,
        );
    }

    public function getItem(string $key): mixed
    {
        try {
            return $this->cache->load(
                key: $key,
            );
        } catch (Throwable $e) {
            throw new UnableToReadCacheException(
                message: $e->getMessage(),
                code: $e->getCode(),
                previous: $e,
            );
        }
    }

    public function saveItem(CacheMetadata $metadata, mixed $item): void
    {
        $dependencies = [];

        if ($metadata->expiration instanceof DateTimeImmutable) {
            $dependencies[Cache::Expire] = $metadata->expiration->format('U.u');
        } elseif (is_int($metadata->expiration)) {
            $dependencies[Cache::Expire] = $metadata->expiration + time();
        }

        if (0 < count($metadata->tags)) {
            $dependencies[Cache::Tags] = $metadata->tags;
        }

        try {
            $this->cache->save(
                key: $metadata->key,
                data: $item,
                dependencies: $dependencies,
            );
        } catch (Throwable $e) {
            throw new UnableToWriteCacheException(
                message: $e->getMessage(),
                code: $e->getCode(),
                previous: $e,
            );
        }
    }

    public function deleteItem(string $key): void
    {
        try {
            $this->cache->remove(
                key: $key,
            );
        } catch (Throwable $e) {
            throw new UnableToDeleteCacheException(
                message: $e->getMessage(),
                code: $e->getCode(),
                previous: $e,
            );
        }
    }

    public function clean(?array $tags = null): void
    {
        $conditions = null !== $tags
            ? [
                Cache::Tags => $tags,
            ]
            : [
                Cache::All => true,
            ];

        try {
            $this->cache->clean(
                conditions: $conditions,
            );
        } catch (Throwable $e) {
            throw new UnableToDeleteCacheException(
                message: $e->getMessage(),
                code: $e->getCode(),
                previous: $e,
            );
        }
    }
}
