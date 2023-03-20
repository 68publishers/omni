<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Infrastructure\InMemory\EventStore;

use SixtyEightPublishers\ArchitectureBundle\Domain\Event\AbstractDomainEvent;
use SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\EventId;
use SixtyEightPublishers\ArchitectureBundle\EventStore\EventCriteria;
use SixtyEightPublishers\ArchitectureBundle\EventStore\EventStoreInterface;
use SixtyEightPublishers\ArchitectureBundle\Infrastructure\InMemory\MemoryStorageInterface;
use function assert;
use function count;
use function in_array;
use function usort;

final class InMemoryEventStore implements EventStoreInterface
{
    public const NAME = 'in_memory';

    private int $idSequence = 0;

    public function __construct(
        private readonly MemoryStorageInterface $memoryStorage,
    ) {}

    public function store(string $aggregateRootClassname, array $events): void
    {
        $section = $this->memoryStorage->section($aggregateRootClassname . ':event_stream');

        foreach ($events as $event) {
            $event = $event->withMetadata([
                self::METADATA_POSITION => ++$this->idSequence,
            ], true);
            $section->add($event->getEventId()->toNative(), $event);
        }
    }

    public function get(string $aggregateRootClassname, EventId $eventId): ?AbstractDomainEvent
    {
        $event = $this->memoryStorage->section($aggregateRootClassname . ':event_stream')->get($eventId->toNative());
        assert($event instanceof AbstractDomainEvent);

        return $event;
    }

    public function find(EventCriteria $criteria): array
    {
        $events = $this->filterEvents($criteria);
        $sortCallback = null;

        switch ($criteria->getSorting()) {
            case $criteria::SORTING_FROM_OLDEST:
                $sortCallback = static fn (AbstractDomainEvent $left, AbstractDomainEvent $right): int =>
                    [$left->getCreatedAt(), $left->getMetadata()[EventStoreInterface::METADATA_POSITION] ?? 0] <=> [$right->getCreatedAt(), $right->getMetadata()[EventStoreInterface::METADATA_POSITION] ?? 0];

                break;
            case $criteria::SORTING_FROM_NEWEST:
                $sortCallback = static fn (AbstractDomainEvent $left, AbstractDomainEvent $right): int =>
                    [$right->getCreatedAt(), $right->getMetadata()[EventStoreInterface::METADATA_POSITION] ?? 0] <=> [$left->getCreatedAt(), $left->getMetadata()[EventStoreInterface::METADATA_POSITION] ?? 0];

                break;
            case $criteria::SORTING_FROM_LOWEST_POSITION:
                $sortCallback = static fn (AbstractDomainEvent $left, AbstractDomainEvent $right): int =>
                ($left->getMetadata()[EventStoreInterface::METADATA_POSITION] ?? 0) <=> ($right->getMetadata()[EventStoreInterface::METADATA_POSITION] ?? 0);

                break;
            case $criteria::SORTING_FROM_HIGHEST_POSITION:
                $sortCallback = static fn (AbstractDomainEvent $left, AbstractDomainEvent $right): int =>
                    ($right->getMetadata()[EventStoreInterface::METADATA_POSITION] ?? 0) <=> ($left->getMetadata()[EventStoreInterface::METADATA_POSITION] ?? 0);

                break;
        }

        if ($sortCallback) {
            usort($events, $sortCallback);
        }

        if (null !== $criteria->getLimit() || null !== $criteria->getOffset()) {
            $events = array_slice($events, $criteria->getOffset() ?? 0, $criteria->getLimit());
        }

        return $events;
    }

    public function count(EventCriteria $criteria): int
    {
        return count($this->filterEvents($criteria));
    }

    /**
     * @return array<AbstractDomainEvent>
     */
    private function filterEvents(EventCriteria $criteria): array
    {
        $filters = [];

        if (null !== $criteria->getAggregateId()) {
            $filters[] = static fn (AbstractDomainEvent $event): bool => $event->getAggregateId()->equals($criteria->getAggregateId());
        }

        if (null !== $criteria->getCreatedBefore()) {
            $filters[] = static fn (AbstractDomainEvent $event): bool => $event->getCreatedAt() <= $criteria->getCreatedBefore();
        }

        if (null !== $criteria->getCreatedAfter()) {
            $filters[] = static fn (AbstractDomainEvent $event): bool => $event->getCreatedAt() >= $criteria->getCreatedAfter();
        }

        if (null !== $criteria->getPositionGreaterThan()) {
            $filters[] = static fn (AbstractDomainEvent $event): bool => ($event->getMetadata()[self::METADATA_POSITION] ?? 0) > $criteria->getPositionGreaterThan();
        }

        if (null !== $criteria->getPositionLessThan()) {
            $filters[] = static fn (AbstractDomainEvent $event): bool => ($event->getMetadata()[self::METADATA_POSITION] ?? 0) < $criteria->getPositionLessThan();
        }

        if (!empty($criteria->getEventNames())) {
            $filters[] = static fn (AbstractDomainEvent $event): bool => in_array($event->getEventName(), $criteria->getEventNames(), true);
        }

        /** @var array<AbstractDomainEvent> $events */
        $events = $this->memoryStorage->section($criteria->getAggregateRootClassname() . ':event_stream')->filter(static function (object $event) use ($filters): bool {
            assert($event instanceof AbstractDomainEvent);

            foreach ($filters as $filter) {
                if (!$filter($event)) {
                    return false;
                }
            }

            return true;
        });

        return $events;
    }
}
