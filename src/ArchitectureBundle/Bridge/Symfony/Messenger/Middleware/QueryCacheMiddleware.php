<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Bridge\Symfony\Messenger\Middleware;

use Psr\Log\LoggerInterface;
use SixtyEightPublishers\ArchitectureBundle\Bridge\Symfony\Messenger\Stamp\RefreshCacheStamp;
use SixtyEightPublishers\ArchitectureBundle\Infrastructure\Cache\CacheInterface;
use SixtyEightPublishers\ArchitectureBundle\Infrastructure\Cache\UnableToReadCacheException;
use SixtyEightPublishers\ArchitectureBundle\Infrastructure\Cache\UnableToWriteCacheException;
use SixtyEightPublishers\ArchitectureBundle\ReadModel\Query\CachableQueryInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;

final class QueryCacheMiddleware implements MiddlewareInterface
{
    public function __construct(
        private readonly CacheInterface $cache,
        private readonly ?LoggerInterface $logger = null,
    ) {}

    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        $message = $envelope->getMessage();

        if (!($message instanceof CachableQueryInterface)) {
            return $stack->next()->handle(
                envelope: $envelope,
                stack: $stack,
            );
        }

        $refreshCache = $envelope->last(RefreshCacheStamp::class) !== null;
        $cacheMetadata = $message->createCacheMetadata();

        try {
            $item = !$refreshCache ? $this->cache->getItem(
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
            $this->cache->saveItem(
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
