<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ProjectionBundle\ProjectionStore;

use SixtyEightPublishers\ProjectionBundle\ProjectionModel\ProjectionModelLocatorInterface;
use Throwable;
use function get_class;

final class ProjectionStoreDecorator implements ProjectionStoreInterface
{
    public function __construct(
        private readonly ProjectionStoreInterface $inner,
        private readonly ProjectionModelLocatorInterface $projectionModelLocator,
    ) {}

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

        if (null === $projectionModel) {
            return;
        }

        try {
            $projectionModel->reset();
        } catch (ProjectionStoreException $e) {
            throw $e;
        } catch (Throwable $e) {
            throw ProjectionStoreException::unableToResetProjectionModel($projectionClassname, get_class($projectionModel), false, $e);
        }
    }
}
