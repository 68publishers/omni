<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Bridge\Symfony\Messenger\Middleware;

use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\StackInterface;
use Symfony\Component\Messenger\Stamp\ConsumedByWorkerStamp;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use SixtyEightPublishers\ArchitectureBundle\Infrastructure\Common\PersistenceAdapter\PersistenceAdapterInterface;

final class StorePingConnectionMiddleware implements MiddlewareInterface
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
		if (NULL !== $envelope->last(ConsumedByWorkerStamp::class)) {
			$this->persistenceAdapter->pingConnection();
		}

		return $stack->next()->handle($envelope, $stack);
	}
}
