<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Bus;

use SixtyEightPublishers\ArchitectureBundle\Event\EventInterface;

interface EventBusInterface
{
	/**
	 * @param \SixtyEightPublishers\ArchitectureBundle\Event\EventInterface $message
	 * @param \Symfony\Component\Messenger\Stamp\StampInterface[]           $stamps
	 *
	 * @return void
	 */
	public function dispatch(EventInterface $message, array $stamps = []): void;
}
