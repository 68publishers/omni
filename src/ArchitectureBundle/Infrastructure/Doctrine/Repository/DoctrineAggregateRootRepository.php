<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Infrastructure\Doctrine\Repository;

use Doctrine\ORM\EntityManagerInterface;
use SixtyEightPublishers\ArchitectureBundle\Domain\AggregateRootInterface;
use SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\AggregateId;
use SixtyEightPublishers\ArchitectureBundle\EventStore\EventStoreException;
use SixtyEightPublishers\ArchitectureBundle\EventStore\EventStoreInterface;
use SixtyEightPublishers\ArchitectureBundle\Infrastructure\Common\EventPublisher\EventPublisherInterface;
use function get_class;

final class DoctrineAggregateRootRepository implements DoctrineAggregateRootRepositoryInterface
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly EventPublisherInterface $eventPublisher,
        private readonly EventStoreInterface $eventStore,
    ) {}

    public function loadAggregateRoot(string $classname, AggregateId $aggregateId): ?object
    {
        return $this->em->find($classname, $aggregateId);
    }

    /**
     * @throws EventStoreException
     */
    public function saveAggregateRoot(AggregateRootInterface $aggregateRoot): void
    {
        $events = $aggregateRoot->popRecordedEvents();
        $aggregateRootClassname = get_class($aggregateRoot);

        $this->em->persist($aggregateRoot);

        $this->eventStore->store($aggregateRootClassname, $events);
        $this->eventPublisher->publish($aggregateRootClassname, $aggregateRoot->getAggregateId(), $events);
    }
}
