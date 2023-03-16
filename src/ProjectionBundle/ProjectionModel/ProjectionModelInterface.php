<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ProjectionBundle\ProjectionModel;

use SixtyEightPublishers\ProjectionBundle\Projection\ProjectionInterface;
use Throwable;

interface ProjectionModelInterface
{
    /**
     * @return class-string<ProjectionInterface>
     */
    public static function getProjectionClassname(): string;

    /**
     * @throws Throwable
     */
    public function reset(): void;

    /**
     * @param array<string, mixed> $values
     */
    public function insert(array $values): void;

    /**
     * @param array<string, mixed> $values
     * @param array<string, mixed> $criteria
     */
    public function update(array $values, array $criteria): void;

    /**
     * @param array<string, mixed> $criteria
     */
    public function delete(array $criteria): void;

    /**
     * @param scalar ...$args
     */
    public function execute(string $action, ...$args): void;
}
