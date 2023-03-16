<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Bridge\Symfony\Messenger\Middleware;

use SixtyEightPublishers\ArchitectureBundle\Infrastructure\Common\PersistenceAdapter\PersistenceAdapterInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;
use Symfony\Component\Messenger\Stamp\ConsumedByWorkerStamp;

final class StoreCloseConnectionMiddleware implements MiddlewareInterface
{
    public function __construct(
        private readonly PersistenceAdapterInterface $persistenceAdapter,
    ) {}

    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        try {
            return $stack->next()->handle($envelope, $stack);
        } finally {
            if (null !== $envelope->last(ConsumedByWorkerStamp::class)) {
                $this->persistenceAdapter->closeConnection();
            }
        }
    }
}
