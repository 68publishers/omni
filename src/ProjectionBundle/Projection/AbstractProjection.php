<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ProjectionBundle\Projection;

use ReflectionException;
use RuntimeException;
use SixtyEightPublishers\ProjectionBundle\ProjectionModel\ProjectionModelInterface;
use SixtyEightPublishers\ProjectionBundle\ProjectionModel\ProjectionModelLocatorInterface;
use function array_slice;
use function assert;
use function call_user_func;
use function explode;
use function implode;
use function sprintf;

abstract class AbstractProjection implements ProjectionInterface
{
    private ?ProjectionModelInterface $resolvedProjectionModel = null;

    public function __construct(
        private readonly ProjectionModelLocatorInterface $projectionModelLocator,
    ) {}

    public static function getProjectionName(): string
    {
        return static::class;
    }

    /**
     * @return iterable<class-string, array{method: string, from_transport: string}>
     * @throws ReflectionException
     */
    public static function getHandledMessages(): iterable
    {
        foreach (call_user_func([static::class, 'defineEvents']) as $eventDefinition) {
            assert($eventDefinition instanceof EventDefinition);

            $methodName = $eventDefinition->methodName ?? 'when' . implode(array_slice(explode('\\', $eventDefinition->eventClassname), -1));

            yield $eventDefinition->eventClassname => [
                'method' => $methodName,
                'from_transport' => static::getProjectionName(),
            ];
        }
    }

    protected function getProjectionModel(): ProjectionModelInterface
    {
        if (null !== $this->resolvedProjectionModel) {
            return $this->resolvedProjectionModel;
        }

        $this->resolvedProjectionModel = $this->projectionModelLocator->resolveForProjectionClassname(static::class);

        if (null === $this->resolvedProjectionModel) {
            throw new RuntimeException(sprintf(
                'Projection model for the projection of type %s is not provided.',
                static::class,
            ));
        }

        return $this->resolvedProjectionModel;
    }
}
