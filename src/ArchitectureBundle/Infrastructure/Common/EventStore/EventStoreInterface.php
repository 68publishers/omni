<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Infrastructure\EventStore;

use SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\AggregateId;

interface EventStoreInterface
{
	/**
	 * @param string                                                                  $aggregateClassname
	 * @param \SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\AggregateId $aggregateId
	 * @param array                                                                   $events
	 *
	 * @return void
	 */
	public function storeEvents(string $aggregateClassname, AggregateId $aggregateId, array $events): void;
}
