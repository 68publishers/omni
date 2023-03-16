<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\EventStore;

use SixtyEightPublishers\ArchitectureBundle\Domain\Event\AbstractDomainEvent;
use SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\EventId;

interface EventStoreInterface
{
    public const METADATA_POSITION = '_position';

    /**
     * @param class-string               $aggregateRootClassname
     * @param array<AbstractDomainEvent> $events
     *
     * @throws EventStoreException
     */
    public function store(string $aggregateRootClassname, array $events): void;

    /**
     * @param  class-string        $aggregateRootClassname
     * @throws EventStoreException
     */
    public function get(string $aggregateRootClassname, EventId $eventId): ?AbstractDomainEvent;

    /**
     * @return array<AbstractDomainEvent>
     * @throws EventStoreException
     */
    public function find(EventCriteria $criteria): array;

    /**
     * @throws EventStoreException
     */
    public function count(EventCriteria $criteria): int;
}
