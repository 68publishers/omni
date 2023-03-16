<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ProjectionBundle\ProjectionStore;

interface ProjectionStoreInterface
{
    /**
     * @param class-string $projectionClassname
     *
     * @return array<class-string, string>
     * @throws ProjectionStoreException
     */
    public function findLastPositions(string $projectionClassname): array;

    /**
     * @param class-string $projectionClassname
     * @param class-string $aggregateClassname
     *
     * @throws ProjectionStoreException
     */
    public function updateLastPosition(string $projectionClassname, string $aggregateClassname, string $position): bool;

    /**
     * @param class-string $projectionClassname
     *
     * @throws ProjectionStoreException
     */
    public function resetProjection(string $projectionClassname): void;
}
