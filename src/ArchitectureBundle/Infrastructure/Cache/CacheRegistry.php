<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Infrastructure\Cache;

final class CacheRegistry implements CacheRegistryInterface
{
    /**
     * @param array<string, CacheInterface> $cacheServices
     */
    public function __construct(
        private readonly CacheInterface $defaultCacheService,
        private array $cacheServices = [],
    ) {}

    public function addCacheService(string $name, CacheInterface $cache): void
    {
        $this->cacheServices[$name] = $cache;
    }

    public function getCache(?string $name): CacheInterface
    {
        if (null === $name) {
            return $this->defaultCacheService;
        }

        if (!isset($this->cacheServices[$name])) {
            throw MissingCacheServiceException::create(
                name: $name,
            );
        }

        return $this->cacheServices[$name];
    }
}
