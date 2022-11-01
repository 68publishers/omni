<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ProjectionBundle\ProjectionModel;

interface ProjectionModelLocatorInterface
{
	public function resolveForProjection(string $projectionClassname): ?ProjectionModelInterface;

	/**
	 * @return iterable<ProjectionModelInterface>
	 */
	public function all(): iterable;
}
