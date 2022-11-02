<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ProjectionBundle\ProjectionModel;

interface ProjectionModelLocatorInterface
{
	public function resolveForProjectionClassname(string $projectionClassname): ?ProjectionModelInterface;

	public function resolveForProjectionName(string $projectionName): ?ProjectionModelInterface;

	/**
	 * @return iterable<ProjectionModelInterface>
	 */
	public function all(): iterable;
}
