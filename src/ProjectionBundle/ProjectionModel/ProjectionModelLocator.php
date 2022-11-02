<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ProjectionBundle\ProjectionModel;

use Nette\DI\Container;

final class ProjectionModelLocator implements ProjectionModelLocatorInterface
{
	private array $serviceNamesByProjectionClassnames;

	private array $serviceNamesByProjectionNames;

	private Container $container;

	/**
	 * @param array<string, string> $serviceNamesByProjectionClassnames
	 * @param array<string, string> $serviceNamesByProjectionNames
	 */
	public function __construct(array $serviceNamesByProjectionClassnames, array $serviceNamesByProjectionNames, Container $container)
	{
		$this->serviceNamesByProjectionClassnames = $serviceNamesByProjectionClassnames;
		$this->serviceNamesByProjectionNames = $serviceNamesByProjectionNames;
		$this->container = $container;
	}

	public function resolveForProjectionClassname(string $projectionClassname): ?ProjectionModelInterface
	{
		if (array_key_exists($projectionClassname, $this->serviceNamesByProjectionClassnames)) {
			$projectionModel = $this->container->getService($this->serviceNamesByProjectionClassnames[$projectionClassname]);

			assert($projectionModel instanceof ProjectionModelInterface);

			return $projectionModel;
		}

		return NULL;
	}

	public function resolveForProjectionName(string $projectionName): ?ProjectionModelInterface
	{
		if (array_key_exists($projectionName, $this->serviceNamesByProjectionNames)) {
			$projectionModel = $this->container->getService($this->serviceNamesByProjectionNames[$projectionName]);

			assert($projectionModel instanceof ProjectionModelInterface);

			return $projectionModel;
		}

		return NULL;
	}

	public function all(): iterable
	{
		foreach (array_unique(array_merge($this->serviceNamesByProjectionClassnames, $this->serviceNamesByProjectionNames)) as $projectionModelServiceName) {
			$projectionModel = $this->container->getService($projectionModelServiceName);

			assert($projectionModel instanceof ProjectionModelInterface);

			yield $projectionModel;
		}
	}
}
