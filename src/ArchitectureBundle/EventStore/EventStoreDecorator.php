<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\EventStore;

use SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\EventId;
use SixtyEightPublishers\ArchitectureBundle\Domain\Event\AbstractDomainEvent;

final class EventStoreDecorator implements EventStoreInterface
{
	private EventStoreInterface $eventStore;

	private EventMetadataExtenderInterface $eventMetadataExtender;

	/**
	 * @param \SixtyEightPublishers\ArchitectureBundle\EventStore\EventStoreInterface            $eventStore
	 * @param \SixtyEightPublishers\ArchitectureBundle\EventStore\EventMetadataExtenderInterface $eventMetadataExtender
	 */
	public function __construct(EventStoreInterface $eventStore, EventMetadataExtenderInterface $eventMetadataExtender)
	{
		$this->eventStore = $eventStore;
		$this->eventMetadataExtender = $eventMetadataExtender;
	}

	/**
	 * {@inheritDoc}
	 */
	public function store(string $aggregateRootClassname, array $events): void
	{
		$events = array_map(
			fn (AbstractDomainEvent $event): AbstractDomainEvent => $this->eventMetadataExtender->extendMetadata($event),
			$events
		);

		$this->eventStore->store($aggregateRootClassname, $events);
	}

	/**
	 * {@inheritDoc}
	 */
	public function get(string $aggregateRootClassname, EventId $eventId): ?AbstractDomainEvent
	{
		return $this->eventStore->get($aggregateRootClassname, $eventId);
	}

	/**
	 * {@inheritDoc}
	 */
	public function find(EventCriteria $criteria): array
	{
		return $this->eventStore->find($criteria);
	}

	/**
	 * {@inheritDoc}
	 */
	public function count(EventCriteria $criteria): int
	{
		return $this->eventStore->count($criteria);
	}
}
