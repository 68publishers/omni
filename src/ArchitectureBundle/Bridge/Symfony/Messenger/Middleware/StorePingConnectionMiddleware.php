<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Bridge\Symfony\Messenger\Middleware;

use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\StackInterface;
use Symfony\Component\Messenger\Stamp\ConsumedByWorkerStamp;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use SixtyEightPublishers\ArchitectureBundle\Infrastructure\Common\StoreAdapter\StoreAdapterInterface;

final class StorePingConnectionMiddleware implements MiddlewareInterface
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
	 */
	public function handle(Envelope $envelope, StackInterface $stack): Envelope
	{
		if (NULL !== $envelope->last(ConsumedByWorkerStamp::class)) {
			$this->storeAdapter->pingConnection();
		}

		return $stack->next()->handle($envelope, $stack);
	}
}
