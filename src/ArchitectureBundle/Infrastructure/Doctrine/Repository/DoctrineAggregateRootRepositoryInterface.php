<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Infrastructure\Doctrine\Repository;

use SixtyEightPublishers\ArchitectureBundle\Domain\AggregateRootInterface;
use SixtyEightPublishers\ArchitectureBundle\Domain\Event\AbstractDomainEvent;
use SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\AggregateIdInterface;

interface DoctrineAggregateRootRepositoryInterface
{
    /**
     * @param class-string $classname
     */
    public function loadAggregateRoot(
        string $classname,
        AggregateIdInterface $aggregateId,
        ?string $entityManagerName = null,
    ): ?object;

    /**
     * @param class-string<AbstractDomainEvent>|null $deleteEventClassname
     */
    public function saveAggregateRoot(
        AggregateRootInterface $aggregateRoot,
        ?string $deleteEventClassname = null,
        ?string $entityManagerName = null,
    ): void;
}
