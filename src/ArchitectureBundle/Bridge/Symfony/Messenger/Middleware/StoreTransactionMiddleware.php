<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Bridge\Symfony\Messenger\Middleware;

use Throwable;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Messenger\Middleware\StackInterface;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use SixtyEightPublishers\ArchitectureBundle\Infrastructure\Common\StoreAdapter\StoreAdapterInterface;

final class StoreTransactionMiddleware implements MiddlewareInterface
{
	private StoreAdapterInterface $storeAdapter;

	/**
	 * @param \SixtyEightPublishers\ArchitectureBundle\Infrastructure\Common\StoreAdapter\StoreAdapterInterface $storeAdapter
	 */
	public function __construct(StoreAdapterInterface $storeAdapter)
	{
		$this->storeAdapter = $storeAdapter;
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
		if ($this->storeAdapter->supportsTransactions()) {
			$this->storeAdapter->beginTransaction();
		}

		try {
			$envelope = $stack->next()->handle($envelope, $stack);

			if ($this->storeAdapter->supportsTransactions()) {
				$this->storeAdapter->commitTransaction();
			}

			return $envelope;
		} catch (Throwable $exception) {
			if ($this->storeAdapter->supportsTransactions()) {
				$this->storeAdapter->rollbackTransaction();
			}

			if ($exception instanceof HandlerFailedException) {
				throw new HandlerFailedException($exception->getEnvelope()->withoutAll(HandledStamp::class), $exception->getNestedExceptions());
			}

			throw $exception;
		}
	}
}
