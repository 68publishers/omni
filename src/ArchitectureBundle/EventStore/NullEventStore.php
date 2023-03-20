<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\EventStore;

use SixtyEightPublishers\ArchitectureBundle\Domain\Event\AbstractDomainEvent;
use SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\EventId;

final class NullEventStore implements EventStoreInterface
{
    public const NAME = 'null';

    public function store(string $aggregateRootClassname, array $events): void
    {
    }

    public function get(string $aggregateRootClassname, EventId $eventId): ?AbstractDomainEvent
    {
        return null;
    }

    public function find(EventCriteria $criteria): array
    {
        return [];
    }

    public function count(EventCriteria $criteria): int
    {
        return 0;
    }
}
