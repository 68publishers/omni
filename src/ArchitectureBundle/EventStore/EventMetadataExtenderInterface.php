<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\EventStore;

use SixtyEightPublishers\ArchitectureBundle\Domain\Event\AbstractDomainEvent;

interface EventMetadataExtenderInterface
{
    public function extendMetadata(AbstractDomainEvent $event): AbstractDomainEvent;
}
