<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Infrastructure\InMemory\Repository;

use SixtyEightPublishers\ArchitectureBundle\Domain\AggregateRootInterface;
use SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\AggregateId;

interface InMemoryAggregateRootRepositoryInterface
{
    /**
     * @param class-string $classname
     */
    public function loadAggregateRoot(string $classname, AggregateId $aggregateId): ?object;

    public function saveAggregateRoot(AggregateRootInterface $aggregateRoot): void;
}
