<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Infrastructure\Doctrine\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use SixtyEightPublishers\ArchitectureBundle\Domain\AggregateRootInterface;
use SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\AggregateIdInterface;
use SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\CompositeAggregateIdInterface;
use SixtyEightPublishers\ArchitectureBundle\EventStore\EventStoreException;
use SixtyEightPublishers\ArchitectureBundle\EventStore\EventStoreInterface;
use SixtyEightPublishers\ArchitectureBundle\Infrastructure\Common\EventPublisher\EventPublisherInterface;
use function assert;
use function get_class;

final class DoctrineAggregateRootRepository implements DoctrineAggregateRootRepositoryInterface
{
    public function __construct(
        private readonly ManagerRegistry $managerRegistry,
        private readonly EventPublisherInterface $eventPublisher,
        private readonly EventStoreInterface $eventStore,
    ) {}

    public function loadAggregateRoot(
        string $classname,
        AggregateIdInterface $aggregateId,
        ?string $entityManagerName = null,
    ): ?object {
        $em = $this->resolveEntityManager(entityManagerName: $entityManagerName);

        return $em->find(
            className: $classname,
            id: $aggregateId instanceof CompositeAggregateIdInterface ? $aggregateId->getValues() : $aggregateId,
        );
    }

    /**
     * @throws EventStoreException
     */
    public function saveAggregateRoot(
        AggregateRootInterface $aggregateRoot,
        ?string $deleteEventClassname = null,
        ?string $entityManagerName = null,
    ): void {
        $em = $this->resolveEntityManager(entityManagerName: $entityManagerName);
        $events = $aggregateRoot->popRecordedEvents();
        $aggregateRootClassname = get_class($aggregateRoot);
        $persist = true;

        if (null !== $deleteEventClassname) {
            foreach ($events as $event) {
                if ($event instanceof $deleteEventClassname && $event->getAggregateId()->equals($aggregateRoot->getAggregateId())) {
                    $em->remove($aggregateRoot);
                    $persist = false;
                }
            }
        }

        if ($persist) {
            $em->persist($aggregateRoot);
        }

        $this->eventStore->store($aggregateRootClassname, $events);

        $em->flush();

        $this->eventPublisher->publish($aggregateRootClassname, $aggregateRoot->getAggregateId(), $events);
    }

    private function resolveEntityManager(?string $entityManagerName): EntityManagerInterface
    {
        $em = $this->managerRegistry->getManager($entityManagerName);
        assert($em instanceof EntityManagerInterface);

        return $em;
    }
}
