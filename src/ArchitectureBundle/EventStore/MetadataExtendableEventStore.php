<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\EventStore;

use SixtyEightPublishers\ArchitectureBundle\Domain\Event\AbstractDomainEvent;
use SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\EventId;
use function array_map;

final class MetadataExtendableEventStore implements EventStoreInterface
{
    public function __construct(
        private readonly EventStoreInterface $eventStore,
        private readonly EventMetadataExtenderInterface $eventMetadataExtender,
    ) {}

    public function store(string $aggregateRootClassname, array $events): void
    {
        $events = array_map(
            fn (AbstractDomainEvent $event): AbstractDomainEvent => $this->eventMetadataExtender->extendMetadata($event),
            $events,
        );

        $this->eventStore->store($aggregateRootClassname, $events);
    }

    public function get(string $aggregateRootClassname, EventId $eventId): ?AbstractDomainEvent
    {
        return $this->eventStore->get($aggregateRootClassname, $eventId);
    }

    public function find(EventCriteria $criteria): array
    {
        return $this->eventStore->find($criteria);
    }

    public function count(EventCriteria $criteria): int
    {
        return $this->eventStore->count($criteria);
    }
}
