<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\EventStore;

use SixtyEightPublishers\ArchitectureBundle\Domain\Event\AbstractDomainEvent;

final class EventMetadataExtenderRegistry implements EventMetadataExtenderInterface
{
    /** @var array<EventMetadataExtenderInterface> */
    private readonly array $eventMetadataExtenders;

    /**
     * @param array<EventMetadataExtenderInterface> $eventMetadataExtenders
     */
    public function __construct(array $eventMetadataExtenders)
    {
        $this->eventMetadataExtenders = (static fn (EventMetadataExtenderInterface ...$eventMetadataExtenders): array => $eventMetadataExtenders)(...$eventMetadataExtenders);
    }

    public function extendMetadata(AbstractDomainEvent $event): AbstractDomainEvent
    {
        foreach ($this->eventMetadataExtenders as $eventMetadataExtender) {
            $event = $eventMetadataExtender->extendMetadata($event);
        }

        return $event;
    }
}
