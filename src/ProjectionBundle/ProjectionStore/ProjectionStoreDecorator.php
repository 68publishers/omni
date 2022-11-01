<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ProjectionBundle\ProjectionStore;

use Throwable;
use SixtyEightPublishers\ProjectionBundle\ProjectionModel\ProjectionModelLocatorInterface;

final class ProjectionStoreDecorator implements ProjectionStoreInterface
{
	private ProjectionStoreInterface $inner;

	private ProjectionModelLocatorInterface $projectionModelLocator;

	public function __construct(ProjectionStoreInterface $inner, ProjectionModelLocatorInterface $projectionModelLocator)
	{
		$this->inner = $inner;
		$this->projectionModelLocator = $projectionModelLocator;
	}

	public function findLastPositions(string $projectionClassname): array
	{
		return $this->inner->findLastPositions($projectionClassname);
	}

	public function updateLastPosition(string $projectionClassname, string $aggregateClassname, string $position): bool
	{
		return $this->inner->updateLastPosition($projectionClassname, $aggregateClassname, $position);
	}

	public function resetProjection(string $projectionClassname): void
	{
		$this->inner->resetProjection($projectionClassname);

		$projectionModel = $this->projectionModelLocator->resolveForProjectionClassname($projectionClassname);

		if (NULL === $projectionModel) {
			return;
		}

		try {
			$projectionModel->reset();
		} catch (ProjectionStoreException $e) {
			throw $e;
		} catch (Throwable $e) {
			throw ProjectionStoreException::unableToResetProjectionModel($projectionClassname, get_class($projectionModel), FALSE, $e);
		}
	}
}
