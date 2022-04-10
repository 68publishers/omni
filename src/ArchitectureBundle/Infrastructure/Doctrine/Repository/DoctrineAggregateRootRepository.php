<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Infrastructure\Doctrine\Repository;

use Doctrine\ORM\EntityManagerInterface;
use SixtyEightPublishers\ArchitectureBundle\Domain\Dto\AggregateId;
use SixtyEightPublishers\ArchitectureBundle\Domain\Event\AggregateDeleted;
use SixtyEightPublishers\ArchitectureBundle\Domain\Aggregate\AggregateRootInterface;
use SixtyEightPublishers\ArchitectureBundle\Infrastructure\Common\EventPublisher\EventPublisherInterface;
use SixtyEightPublishers\ArchitectureBundle\Infrastructure\Common\Repository\AggregateRootRepositoryInterface;

final class DoctrineAggregateRootRepository implements AggregateRootRepositoryInterface
{
	private EntityManagerInterface $em;
	
	private EventPublisherInterface $eventPublisher;

	/**
	 * @param \Doctrine\ORM\EntityManagerInterface                                                                  $em
	 * @param \SixtyEightPublishers\ArchitectureBundle\Infrastructure\Common\EventPublisher\EventPublisherInterface $eventPublisher
	 */
	public function __construct(EntityManagerInterface $em, EventPublisherInterface $eventPublisher)
	{
		$this->em = $em;
		$this->eventPublisher = $eventPublisher;
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

		if ($this->containsDeletedEvent($events)) {
			$this->em->remove($aggregateRoot);
		} else {
			$this->em->persist($aggregateRoot);
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
