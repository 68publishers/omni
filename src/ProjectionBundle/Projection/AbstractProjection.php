<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ProjectionBundle\Projection;

use RuntimeException;
use SixtyEightPublishers\ProjectionBundle\ProjectionModel\ProjectionModelInterface;
use SixtyEightPublishers\ProjectionBundle\ProjectionModel\ProjectionModelLocatorInterface;

abstract class AbstractProjection implements ProjectionInterface
{
	private ProjectionModelLocatorInterface $projectionModelLocator;

	private ?ProjectionModelInterface $resolvedProjectionModel = NULL;

	public function __construct(ProjectionModelLocatorInterface $projectionModelLocator)
	{
		$this->projectionModelLocator = $projectionModelLocator;
	}

	public static function projectionName(): string
	{
		return static::class;
	}

	protected function projectionModel(): ProjectionModelInterface
	{
		if (NULL !== $this->resolvedProjectionModel) {
			return $this->resolvedProjectionModel;
		}

		$this->resolvedProjectionModel = $this->projectionModelLocator->resolveForProjectionClassname(static::class);

		if (NULL === $this->resolvedProjectionModel) {
			throw new RuntimeException(sprintf(
				'Projection model for the projection of type %s is not provided.',
				static::class
			));
		}

		return $this->resolvedProjectionModel;
	}
}
