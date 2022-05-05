<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Infrastructure\Common\Repository;

use SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\AggregateId;
use SixtyEightPublishers\ArchitectureBundle\Domain\Aggregate\AggregateRootInterface;

interface AggregateRootRepositoryInterface
{
	/**
	 * @param string                                                                  $classname
	 * @param \SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\AggregateId $aggregateId
	 *
	 * @return object|NULL
	 */
	public function loadAggregateRoot(string $classname, AggregateId $aggregateId): ?object;

	/**
	 * @param \SixtyEightPublishers\ArchitectureBundle\Domain\Aggregate\AggregateRootInterface $aggregateRoot
	 *
	 * @return void
	 */
	public function saveAggregateRoot(AggregateRootInterface $aggregateRoot): void;
}
