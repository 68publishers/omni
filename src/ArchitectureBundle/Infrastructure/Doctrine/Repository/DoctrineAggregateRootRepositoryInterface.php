<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Infrastructure\Doctrine\Repository;

use SixtyEightPublishers\ArchitectureBundle\Domain\AggregateRootInterface;
use SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\AggregateIdInterface;

interface DoctrineAggregateRootRepositoryInterface
{
    /**
     * @param class-string $classname
     */
    public function loadAggregateRoot(string $classname, AggregateIdInterface $aggregateId): ?object;

    public function saveAggregateRoot(AggregateRootInterface $aggregateRoot): void;
}
