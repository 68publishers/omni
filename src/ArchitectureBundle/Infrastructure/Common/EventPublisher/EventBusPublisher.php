<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Infrastructure\Common\EventPublisher;

use SixtyEightPublishers\ArchitectureBundle\Bus\EventBusInterface;
use SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\AggregateId;

final class EventBusPublisher implements EventPublisherInterface
{
	private EventBusInterface $eventBus;

	/**
	 * @param \SixtyEightPublishers\ArchitectureBundle\Bus\EventBusInterface $eventBus
	 */
	public function __construct(EventBusInterface $eventBus)
	{
		$this->eventBus = $eventBus;
	}

	/**
	 * {@inheritDoc}
	 */
	public function publish(string $aggregateClassname, AggregateId $aggregateId, array $events): void
	{
		foreach ($events as $event) {
			bdump($event); // @todo: remove
			$this->eventBus->dispatch($event);
		}
	}
}
