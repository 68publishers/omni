<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\EventStore;

use SixtyEightPublishers\ArchitectureBundle\Domain\Event\AbstractDomainEvent;
use SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\EventId;

final class ResolvableEventStore implements EventStoreInterface
{
    /**
     * @param array<string, EventStoreInterface> $eventStores
     */
    public function __construct(
        private readonly EventStoreNameResolver $eventStoreNameResolver,
        private readonly array $eventStores,
    ) {}

    public function store(string $aggregateRootClassname, array $events): void
    {
        $this->resolveEventStore($aggregateRootClassname)->store($aggregateRootClassname, $events);
    }

    public function get(string $aggregateRootClassname, EventId $eventId): ?AbstractDomainEvent
    {
        return $this->resolveEventStore($aggregateRootClassname)->get($aggregateRootClassname, $eventId);
    }

    public function find(EventCriteria $criteria): array
    {
        return $this->resolveEventStore($criteria->getAggregateRootClassname())->find($criteria);
    }

    public function count(EventCriteria $criteria): int
    {
        return $this->resolveEventStore($criteria->getAggregateRootClassname())->count($criteria);
    }

    /**
     * @param class-string $aggregateRootClassname
     *
     * @throws EventStoreException
     */
    private function resolveEventStore(string $aggregateRootClassname): EventStoreInterface
    {
        $eventStoreName = $this->eventStoreNameResolver->resolve($aggregateRootClassname);

        if (!isset($this->eventStores[$eventStoreName])) {
            throw EventStoreException::unableToResolveEventStore($aggregateRootClassname, $eventStoreName);
        }

        return $this->eventStores[$eventStoreName];
    }
}
