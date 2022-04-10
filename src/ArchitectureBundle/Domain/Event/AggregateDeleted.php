<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Domain\Event;

use SixtyEightPublishers\ArchitectureBundle\Domain\Dto\AggregateId;

final class AggregateDeleted extends AbstractDomainEvent
{
	/**
	 * @param \SixtyEightPublishers\ArchitectureBundle\Domain\Dto\AggregateId $aggregateId
	 *
	 * @return static
	 */
	public static function create(AggregateId $aggregateId): self
	{
		return self::occur($aggregateId->toString(), []);
	}
}
