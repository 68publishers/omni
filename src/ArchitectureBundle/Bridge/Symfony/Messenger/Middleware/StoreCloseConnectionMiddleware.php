<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Bridge\Symfony\Messenger\Middleware;

use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\StackInterface;
use Symfony\Component\Messenger\Stamp\ConsumedByWorkerStamp;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use SixtyEightPublishers\ArchitectureBundle\Infrastructure\Common\PersistenceAdapter\PersistenceAdapterInterface;

final class StoreCloseConnectionMiddleware implements MiddlewareInterface
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
	 */
	public function handle(Envelope $envelope, StackInterface $stack): Envelope
	{
		try {
			return $stack->next()->handle($envelope, $stack);
		} finally {
			if (NULL !== $envelope->last(ConsumedByWorkerStamp::class)) {
				$this->persistenceAdapter->closeConnection();
			}
		}
	}
}
