<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ProjectionBundle\ProjectionModel;

use Nette\DI\Container;
use function array_key_exists;
use function array_merge;
use function array_unique;
use function assert;

final class ProjectionModelLocator implements ProjectionModelLocatorInterface
{
    /**
     * @param array<class-string, string> $serviceNamesByProjectionClassnames
     * @param array<string, string>       $serviceNamesByProjectionNames
     */
    public function __construct(
        private readonly array $serviceNamesByProjectionClassnames,
        private readonly array $serviceNamesByProjectionNames,
        private readonly Container $container,
    ) {}

    public function resolveForProjectionClassname(string $projectionClassname): ?ProjectionModelInterface
    {
        if (array_key_exists($projectionClassname, $this->serviceNamesByProjectionClassnames)) {
            $projectionModel = $this->container->getService($this->serviceNamesByProjectionClassnames[$projectionClassname]);

            assert($projectionModel instanceof ProjectionModelInterface);

            return $projectionModel;
        }

        return null;
    }

    public function resolveForProjectionName(string $projectionName): ?ProjectionModelInterface
    {
        if (array_key_exists($projectionName, $this->serviceNamesByProjectionNames)) {
            $projectionModel = $this->container->getService($this->serviceNamesByProjectionNames[$projectionName]);

            assert($projectionModel instanceof ProjectionModelInterface);

            return $projectionModel;
        }

        return null;
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
