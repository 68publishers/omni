<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Infrastructure\Common\EventPublisher;

use SixtyEightPublishers\ArchitectureBundle\Domain\Event\AbstractDomainEvent;
use SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\AggregateIdInterface;

interface EventPublisherInterface
{
    /**
     * @param class-string               $aggregateClassname
     * @param array<AbstractDomainEvent> $events
     */
    public function publish(string $aggregateClassname, AggregateIdInterface $aggregateId, array $events): void;
}
