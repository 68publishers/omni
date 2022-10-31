<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ProjectionBundle\Projection;

abstract class AbstractProjection implements ProjectionInterface
{
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
}
