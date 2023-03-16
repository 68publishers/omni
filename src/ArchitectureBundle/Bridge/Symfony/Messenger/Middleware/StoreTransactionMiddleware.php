<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Bridge\Symfony\Messenger\Middleware;

use SixtyEightPublishers\ArchitectureBundle\Infrastructure\Common\PersistenceAdapter\PersistenceAdapterInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Throwable;

final class StoreTransactionMiddleware implements MiddlewareInterface
{
    public function __construct(
        private readonly PersistenceAdapterInterface $persistenceAdapter,
    ) {}

    /**
     * @throws Throwable
     */
    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        if ($this->persistenceAdapter->supportsTransactions()) {
            $this->persistenceAdapter->beginTransaction();
        }

        try {
            $envelope = $stack->next()->handle($envelope, $stack);

            if ($this->persistenceAdapter->supportsTransactions()) {
                $this->persistenceAdapter->commitTransaction();
            }

            return $envelope;
        } catch (Throwable $exception) {
            if ($this->persistenceAdapter->supportsTransactions()) {
                $this->persistenceAdapter->rollbackTransaction();
            }

            if ($exception instanceof HandlerFailedException) {
                throw new HandlerFailedException($exception->getEnvelope()->withoutAll(HandledStamp::class), $exception->getNestedExceptions());
            }

            throw $exception;
        }
    }
}
