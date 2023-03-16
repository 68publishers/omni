<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Infrastructure\InMemory\Repository;

use SixtyEightPublishers\ArchitectureBundle\Domain\AggregateRootInterface;
use SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\AggregateId;
use SixtyEightPublishers\ArchitectureBundle\Infrastructure\Common\EventPublisher\EventPublisherInterface;
use SixtyEightPublishers\ArchitectureBundle\Infrastructure\Common\Repository\AggregateRootRepositoryInterface;
use SixtyEightPublishers\ArchitectureBundle\Infrastructure\InMemory\MemoryStorageInterface;
use function get_class;

final class InMemoryAggregateRootRepository implements AggregateRootRepositoryInterface
{
    public function __construct(
        private readonly MemoryStorageInterface $memoryStorage,
        private readonly EventPublisherInterface $eventPublisher,
    ) {}

    public function loadAggregateRoot(string $classname, AggregateId $aggregateId): ?object
    {
        return $this->memoryStorage->section($classname)->get($aggregateId->toNative());
    }

    public function saveAggregateRoot(AggregateRootInterface $aggregateRoot): void
    {
        $events = $aggregateRoot->popRecordedEvents();

        $this->memoryStorage->section(get_class($aggregateRoot))->add($aggregateRoot->getAggregateId()->toNative(), $aggregateRoot);
        $this->eventPublisher->publish(get_class($aggregateRoot), $aggregateRoot->getAggregateId(), $events);
    }
}
