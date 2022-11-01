<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ProjectionBundle\ProjectionModel;

use Nette\DI\Container;

final class ProjectionModelLocator implements ProjectionModelLocatorInterface
{
	private array $projectionModelServiceNames;

	private Container $container;

	/**
	 * @param array<string, string> $projectionModelServiceNames
	 */
	public function __construct(array $projectionModelServiceNames, Container $container)
	{
		$this->projectionModelServiceNames = $projectionModelServiceNames;
		$this->container = $container;
	}

	public function resolveForProjection(string $projectionClassname): ?ProjectionModelInterface
	{
		if (array_key_exists($projectionClassname, $this->projectionModelServiceNames)) {
			$projectionModel = $this->container->getService($this->projectionModelServiceNames[$projectionClassname]);

			assert($projectionModel instanceof ProjectionModelInterface);

			return $projectionModel;
		}

		return NULL;
	}

	public function all(): iterable
	{
		foreach ($this->projectionModelServiceNames as $projectionModelServiceName) {
			$projectionModel = $this->container->getService($projectionModelServiceName);

			assert($projectionModel instanceof ProjectionModelInterface);

			yield $projectionModel;
		}
	}
}
