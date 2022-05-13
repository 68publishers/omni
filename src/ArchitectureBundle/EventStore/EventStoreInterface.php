<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\EventStore;

use SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\EventId;
use SixtyEightPublishers\ArchitectureBundle\Domain\Event\AbstractDomainEvent;

interface EventStoreInterface
{
	/**
	 * @param string $aggregateRootClassname
	 * @param array  $events
	 *
	 * @return void
	 */
	public function store(string $aggregateRootClassname, array $events): void;

	/**
	 * @param string                                                              $aggregateRootClassname
	 * @param \SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\EventId $eventId
	 *
	 * @return \SixtyEightPublishers\ArchitectureBundle\Domain\Event\AbstractDomainEvent|NULL
	 */
	public function get(string $aggregateRootClassname, EventId $eventId): ?AbstractDomainEvent;

	/**
	 * @param \SixtyEightPublishers\ArchitectureBundle\EventStore\EventCriteria $criteria
	 *
	 * @return \SixtyEightPublishers\ArchitectureBundle\Domain\Event\AbstractDomainEvent[]
	 */
	public function find(EventCriteria $criteria): array;
}
