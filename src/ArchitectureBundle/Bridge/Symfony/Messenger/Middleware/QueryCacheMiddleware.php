<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Bridge\Symfony\Messenger\Middleware;

use Psr\Log\LoggerInterface;
use SixtyEightPublishers\ArchitectureBundle\Bridge\Symfony\Messenger\Stamp\CacheStamp;
use SixtyEightPublishers\ArchitectureBundle\Bridge\Symfony\Messenger\Stamp\RefreshCacheStamp;
use SixtyEightPublishers\ArchitectureBundle\Infrastructure\Cache\CacheRegistryInterface;
use SixtyEightPublishers\ArchitectureBundle\Infrastructure\Cache\UnableToReadCacheException;
use SixtyEightPublishers\ArchitectureBundle\Infrastructure\Cache\UnableToWriteCacheException;
use SixtyEightPublishers\ArchitectureBundle\ReadModel\Query\CachableQueryInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;

final class QueryCacheMiddleware implements MiddlewareInterface
{
    public function __construct(
        private readonly CacheRegistryInterface $cacheRegistry,
        private readonly ?LoggerInterface $logger = null,
    ) {}

    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        $message = $envelope->getMessage();
        $cacheStamp = $envelope->last(CacheStamp::class);

        if (!($message instanceof CachableQueryInterface) && !($cacheStamp instanceof CacheStamp)) {
            return $stack->next()->handle(
                envelope: $envelope,
                stack: $stack,
            );
        }

        $cacheMetadata = $cacheStamp instanceof CacheStamp ? $cacheStamp->metadata : $message->createCacheMetadata();
        $cache = $this->cacheRegistry->getCache(
            name: $cacheMetadata->cacheName,
        );
        $refreshCache = $envelope->last(RefreshCacheStamp::class) !== null;

        try {
            $item = !$refreshCache ? $cache->getItem(
                key: $cacheMetadata->key,
            ) : null;
        } catch (UnableToReadCacheException $e) {
            if (null === $this->logger) {
                throw $e;
            }

            $this->logger->error(
                message: $e->getMessage(),
                context: [
                    'exception' => $e,
                ],
            );

            $item = null;
        }

        if (null !== $item) {
            return $item;
        }

        $item = $stack->next()->handle(
            envelope: $envelope,
            stack: $stack,
        );

        try {
            $cache->saveItem(
                metadata: $cacheMetadata,
                item: $item,
            );
        } catch (UnableToWriteCacheException $e) {
            if (null === $this->logger) {
                throw $e;
            }

            $this->logger->error(
                message: $e->getMessage(),
                context: [
                    'exception' => $e,
                ],
            );
        }

        return $item;
    }
}
