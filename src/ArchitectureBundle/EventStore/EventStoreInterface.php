<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\EventStore;

use SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\EventId;
use SixtyEightPublishers\ArchitectureBundle\Domain\Event\AbstractDomainEvent;

interface EventStoreInterface
{
	public const METADATA_POSITION = '_position';

	/**
	 * @param string $aggregateRootClassname
	 * @param array  $events
	 *
	 * @return void
	 * @throws \SixtyEightPublishers\ArchitectureBundle\EventStore\EventStoreException
	 */
	public function store(string $aggregateRootClassname, array $events): void;

	/**
	 * @param string                                                              $aggregateRootClassname
	 * @param \SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\EventId $eventId
	 *
	 * @return \SixtyEightPublishers\ArchitectureBundle\Domain\Event\AbstractDomainEvent|NULL
	 * @throws \SixtyEightPublishers\ArchitectureBundle\EventStore\EventStoreException
	 */
	public function get(string $aggregateRootClassname, EventId $eventId): ?AbstractDomainEvent;

	/**
	 * @param \SixtyEightPublishers\ArchitectureBundle\EventStore\EventCriteria $criteria
	 *
	 * @return \SixtyEightPublishers\ArchitectureBundle\Domain\Event\AbstractDomainEvent[]
	 * @throws \SixtyEightPublishers\ArchitectureBundle\EventStore\EventStoreException
	 */
	public function find(EventCriteria $criteria): array;

	/**
	 * @param \SixtyEightPublishers\ArchitectureBundle\EventStore\EventCriteria $criteria
	 *
	 * @return int
	 * @throws \SixtyEightPublishers\ArchitectureBundle\EventStore\EventStoreException
	 */
	public function count(EventCriteria $criteria): int;
}
