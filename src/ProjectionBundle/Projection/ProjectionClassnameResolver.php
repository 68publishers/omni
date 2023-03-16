<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ProjectionBundle\Projection;

use InvalidArgumentException;
use function assert;
use function is_subclass_of;
use function sprintf;

final class ProjectionClassnameResolver
{
    /**
     * @param array<class-string> $projectionClassnames
     */
    public function __construct(
        private readonly array $projectionClassnames,
    ) {}

    /**
     * @return class-string
     * @throws InvalidArgumentException
     */
    public function resolve(string $projectionName): string
    {
        foreach ($this->projectionClassnames as $projectionClassname) {
            assert(is_subclass_of($projectionClassname, ProjectionInterface::class, true));

            if ($projectionName === $projectionClassname::getProjectionName()) {
                return $projectionClassname;
            }
        }

        throw new InvalidArgumentException(sprintf(
            'Projection with the name "%s" not found.',
            $projectionName,
        ));
    }
}
