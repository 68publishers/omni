<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Infrastructure\InMemory\Repository;

use SixtyEightPublishers\ArchitectureBundle\Domain\Event\AggregateDeleted;
use SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\AggregateId;
use SixtyEightPublishers\ArchitectureBundle\Domain\Aggregate\AggregateRootInterface;
use SixtyEightPublishers\ArchitectureBundle\Infrastructure\InMemory\MemoryStorageInterface;
use SixtyEightPublishers\ArchitectureBundle\Infrastructure\Common\EventPublisher\EventPublisherInterface;
use SixtyEightPublishers\ArchitectureBundle\Infrastructure\Common\Repository\AggregateRootRepositoryInterface;

final class InMemoryAggregateRootRepository implements AggregateRootRepositoryInterface
{
	private MemoryStorageInterface $memoryStorage;

	private EventPublisherInterface $eventPublisher;

	/**
	 * @param \SixtyEightPublishers\ArchitectureBundle\Infrastructure\InMemory\MemoryStorageInterface               $memoryStorage
	 * @param \SixtyEightPublishers\ArchitectureBundle\Infrastructure\Common\EventPublisher\EventPublisherInterface $eventPublisher
	 */
	public function __construct(MemoryStorageInterface $memoryStorage, EventPublisherInterface $eventPublisher)
	{
		$this->memoryStorage = $memoryStorage;
		$this->eventPublisher = $eventPublisher;
	}

	/**
	 * {@inheritDoc}
	 */
	public function loadAggregateRoot(string $classname, AggregateId $aggregateId): ?object
	{
		return $this->memoryStorage->section($classname)->get($aggregateId->toString());
	}

	/**
	 * {@inheritDoc}
	 */
	public function saveAggregateRoot(AggregateRootInterface $aggregateRoot): void
	{
		$events = $aggregateRoot->popRecordedEvents();

		if ($this->containsDeletedEvent($events)) {
			$this->memoryStorage->section(get_class($aggregateRoot))->remove($aggregateRoot->aggregateId()->toString());
		} else {
			$this->memoryStorage->section(get_class($aggregateRoot))->add($aggregateRoot->aggregateId()->toString(), $aggregateRoot);
		}

		$this->eventPublisher->publish(get_class($aggregateRoot), $aggregateRoot->aggregateId(), $events);
	}

	/**
	 * @param array $events
	 *
	 * @return bool
	 */
	private function containsDeletedEvent(array $events): bool
	{
		foreach ($events as $event) {
			if ($event instanceof AggregateDeleted) {
				return TRUE;
			}
		}

		return FALSE;
	}
}
