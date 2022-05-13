<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Bridge\Symfony\Messenger\Middleware;

use Throwable;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Messenger\Middleware\StackInterface;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use SixtyEightPublishers\ArchitectureBundle\Infrastructure\Common\PersistenceAdapter\PersistenceAdapterInterface;

final class StoreTransactionMiddleware implements MiddlewareInterface
{
	private PersistenceAdapterInterface $persistenceAdapter;

	/**
	 * @param \SixtyEightPublishers\ArchitectureBundle\Infrastructure\Common\PersistenceAdapter\PersistenceAdapterInterface $persistenceAdapter
	 */
	public function __construct(PersistenceAdapterInterface $persistenceAdapter)
	{
		$this->persistenceAdapter = $persistenceAdapter;
	}

	/**
	 * @param \Symfony\Component\Messenger\Envelope                  $envelope
	 * @param \Symfony\Component\Messenger\Middleware\StackInterface $stack
	 *
	 * @return \Symfony\Component\Messenger\Envelope
	 * @throws \Throwable
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
