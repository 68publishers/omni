<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Infrastructure\InMemory\Repository;

use SixtyEightPublishers\ArchitectureBundle\Domain\AggregateRootInterface;
use SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\AggregateIdInterface;
use SixtyEightPublishers\ArchitectureBundle\Infrastructure\Common\EventPublisher\EventPublisherInterface;
use SixtyEightPublishers\ArchitectureBundle\Infrastructure\InMemory\MemoryStorageInterface;
use function get_class;

final class InMemoryAggregateRootRepository implements InMemoryAggregateRootRepositoryInterface
{
    public function __construct(
        private readonly MemoryStorageInterface $memoryStorage,
        private readonly EventPublisherInterface $eventPublisher,
    ) {}

    public function loadAggregateRoot(string $classname, AggregateIdInterface $aggregateId): ?object
    {
        return $this->memoryStorage->section($classname)->get($aggregateId->toString());
    }

    public function saveAggregateRoot(AggregateRootInterface $aggregateRoot): void
    {
        $events = $aggregateRoot->popRecordedEvents();

        $this->memoryStorage->section(get_class($aggregateRoot))->add($aggregateRoot->getAggregateId()->toString(), $aggregateRoot);
        $this->eventPublisher->publish(get_class($aggregateRoot), $aggregateRoot->getAggregateId(), $events);
    }
}
