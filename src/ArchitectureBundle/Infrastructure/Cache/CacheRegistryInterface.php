<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Infrastructure\Cache;

interface CacheRegistryInterface
{
    /**
     * @throws MissingCacheServiceException
     */
    public function getCache(?string $name): CacheInterface;
}
