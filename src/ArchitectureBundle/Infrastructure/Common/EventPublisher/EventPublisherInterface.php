<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Infrastructure\Common\EventPublisher;

use SixtyEightPublishers\ArchitectureBundle\Domain\Dto\AggregateId;

interface EventPublisherInterface
{
	/**
	 * @param string                                                                      $aggregateClassname
	 * @param \SixtyEightPublishers\ArchitectureBundle\Domain\Dto\AggregateId             $aggregateId
	 * @param \SixtyEightPublishers\ArchitectureBundle\Domain\Event\AbstractDomainEvent[] $events
	 *
	 * @return void
	 */
	public function publish(string $aggregateClassname, AggregateId $aggregateId, array $events): void;
}
