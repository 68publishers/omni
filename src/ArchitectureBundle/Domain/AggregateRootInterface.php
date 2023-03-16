<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Domain;

use SixtyEightPublishers\ArchitectureBundle\Domain\Event\AbstractDomainEvent;
use SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\AggregateId;

interface AggregateRootInterface
{
    public function getAggregateId(): AggregateId;

    public function getVersion(): int;

    /**
     * @internal
     *
     * @return AbstractDomainEvent[]
     */
    public function popRecordedEvents(): array;
}
