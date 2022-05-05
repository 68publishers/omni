<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Domain\Aggregate;

use SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\AggregateId;

interface AggregateRootInterface
{
	/**
	 * @return \SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\AggregateId
	 */
	public function aggregateId(): AggregateId;

	/**
	 * @return int
	 */
	public function version(): int;

	/**
	 * @internal
	 *
	 * @return \SixtyEightPublishers\ArchitectureBundle\Domain\Event\AbstractDomainEvent[]
	 */
	public function popRecordedEvents(): array;
}
