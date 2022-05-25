<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Infrastructure\Doctrine\Repository;

use Doctrine\ORM\EntityManagerInterface;
use SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\AggregateId;
use SixtyEightPublishers\ArchitectureBundle\EventStore\EventStoreInterface;
use SixtyEightPublishers\ArchitectureBundle\Domain\Aggregate\AggregateRootInterface;
use SixtyEightPublishers\ArchitectureBundle\Infrastructure\Common\EventPublisher\EventPublisherInterface;
use SixtyEightPublishers\ArchitectureBundle\Infrastructure\Common\Repository\AggregateRootRepositoryInterface;

final class DoctrineAggregateRootRepository implements AggregateRootRepositoryInterface
{
	private EntityManagerInterface $em;
	
	private EventPublisherInterface $eventPublisher;

	private EventStoreInterface $eventStore;

	/**
	 * @param \Doctrine\ORM\EntityManagerInterface                                                                  $em
	 * @param \SixtyEightPublishers\ArchitectureBundle\Infrastructure\Common\EventPublisher\EventPublisherInterface $eventPublisher
	 * @param \SixtyEightPublishers\ArchitectureBundle\EventStore\EventStoreInterface                               $eventStore
	 */
	public function __construct(EntityManagerInterface $em, EventPublisherInterface $eventPublisher, EventStoreInterface $eventStore)
	{
		$this->em = $em;
		$this->eventPublisher = $eventPublisher;
		$this->eventStore = $eventStore;
	}

	/**
	 * {@inheritDoc}
	 */
	public function loadAggregateRoot(string $classname, AggregateId $aggregateId): ?object
	{
		return $this->em->find($classname, $aggregateId->id());
	}

	/**
	 * {@inheritDoc}
	 */
	public function saveAggregateRoot(AggregateRootInterface $aggregateRoot): void
	{
		$events = $aggregateRoot->popRecordedEvents();
		$aggregateRootClassname = get_class($aggregateRoot);

		$this->em->persist($aggregateRoot);

		$this->eventStore->store($aggregateRootClassname, $events);
		$this->eventPublisher->publish($aggregateRootClassname, $aggregateRoot->aggregateId(), $events);
	}
}
