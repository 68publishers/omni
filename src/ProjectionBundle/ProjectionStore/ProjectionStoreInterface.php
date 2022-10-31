<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ProjectionBundle\ProjectionStore;

interface ProjectionStoreInterface
{
	/**
	 * @return array<string, string>
	 * @throws \SixtyEightPublishers\ProjectionBundle\ProjectionStore\ProjectionStoreException
	 */
	public function findLastPositions(string $projectionClassname): array;

	/**
	 * @throws \SixtyEightPublishers\ProjectionBundle\ProjectionStore\ProjectionStoreException
	 */
	public function updateLastPosition(string $projectionClassname, string $aggregateClassname, string $position): bool;
}
