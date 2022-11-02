<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ProjectionBundle\Projection;

use InvalidArgumentException;

final class ProjectionClassnameResolver
{
	private array $projectionClassnames;

	/**
	 * @param array<string> $projectionClassnames
	 */
	public function __construct(array $projectionClassnames)
	{
		$this->projectionClassnames = $projectionClassnames;
	}

	/**
	 * @throws \InvalidArgumentException
	 */
	public function resolve(string $projectionName): string
	{
		foreach ($this->projectionClassnames as $projectionClassname) {
			assert(is_subclass_of($projectionClassname, ProjectionInterface::class, TRUE));

			if ($projectionName === $projectionClassname::projectionName()) {
				return $projectionClassname;
			}
		}

		throw new InvalidArgumentException(sprintf(
			'Projection with the name "%s" not found.',
			$projectionName
		));
	}
}
