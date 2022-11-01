<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ProjectionBundle\Projection;

use RuntimeException;
use SixtyEightPublishers\ProjectionBundle\ProjectionModel\ProjectionModelInterface;
use SixtyEightPublishers\ProjectionBundle\ProjectionModel\ProjectionModelLocatorInterface;

abstract class AbstractProjection implements ProjectionInterface
{
	private ProjectionModelLocatorInterface $projectionModelLocator;

	private ?ProjectionModelInterface $resolvedProjectionModel = NULL;

	public function __construct(ProjectionModelLocatorInterface $projectionModelLocator)
	{
		$this->projectionModelLocator = $projectionModelLocator;
	}

	public static function projectionName(): string
	{
		return static::class;
	}

	/**
	 * @throws \ReflectionException
	 */
	public static function getHandledMessages(): iterable
	{
		foreach (call_user_func([static::class, 'defineEvents']) as $eventDefinition) {
			assert($eventDefinition instanceof EventDefinition);

			$methodName = $eventDefinition->methodName ?? 'when' . implode(array_slice(explode('\\', $eventDefinition->eventClassname), -1));

			yield $eventDefinition->eventClassname => [
				'method' => $methodName,
				'from_transport' => static::projectionName(),
			];
		}
	}

	protected function projectionModel(): ProjectionModelInterface
	{
		if (NULL !== $this->resolvedProjectionModel) {
			return $this->resolvedProjectionModel;
		}

		$this->resolvedProjectionModel = $this->projectionModelLocator->resolveForProjectionClassname(static::class);

		if (NULL === $this->resolvedProjectionModel) {
			throw new RuntimeException(sprintf(
				'Projection model for the projection of type %s is not provided.',
				static::class
			));
		}

		return $this->resolvedProjectionModel;
	}
}
