<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\EventStore;

final class EventStoreNameResolver
{
    /** @var array<class-string, string> */
    private array $eventStoreNameByAggregateClassname = [];

    public function __construct(
        private readonly string $defaultEventStoreName,
    ) {}

    /**
     * @param class-string $aggregateClassname
     */
    public function registerAggregateClassname(string $aggregateClassname, string $eventStoreName): void
    {
        $this->eventStoreNameByAggregateClassname[$aggregateClassname] = $eventStoreName;
    }

    /**
     * @param class-string $aggregateClassname
     */
    public function resolve(string $aggregateClassname): string
    {
        return $this->eventStoreNameByAggregateClassname[$aggregateClassname] ?? $this->defaultEventStoreName;
    }
}
