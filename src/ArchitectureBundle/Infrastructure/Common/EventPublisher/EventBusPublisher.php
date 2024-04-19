<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Infrastructure\Common\EventPublisher;

use SixtyEightPublishers\ArchitectureBundle\Bus\EventBusInterface;
use SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\AggregateIdInterface;

final class EventBusPublisher implements EventPublisherInterface
{
    private EventBusInterface $eventBus;

    public function __construct(EventBusInterface $eventBus)
    {
        $this->eventBus = $eventBus;
    }

    public function publish(string $aggregateClassname, AggregateIdInterface $aggregateId, array $events): void
    {
        foreach ($events as $event) {
            bdump($event); // @todo: remove
            $this->eventBus->dispatch($event);
        }
    }
}
