<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\EventStore;

use SixtyEightPublishers\ArchitectureBundle\Domain\Event\AbstractDomainEvent;

interface EventMetadataExtenderInterface
{
	/**
	 * @param \SixtyEightPublishers\ArchitectureBundle\Domain\Event\AbstractDomainEvent $event
	 *
	 * @return \SixtyEightPublishers\ArchitectureBundle\Domain\Event\AbstractDomainEvent
	 */
	public function extendMetadata(AbstractDomainEvent $event): AbstractDomainEvent;
}
