<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ProjectionBundle\Projection;

final class EventDefinition
{
    /**
     * @param class-string $aggregateRootClassname
     * @param class-string $eventClassname
     */
    public function __construct(
        public readonly string $aggregateRootClassname,
        public readonly string $eventClassname,
        public readonly ?string $methodName = null,
    ) {}
}
