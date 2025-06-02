<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ProjectionBundle\Projection;

interface ProjectionInterface
{
	public static function projectionName(): string;

	/**
	 * @return iterable<EventDefinition>
	 */
	public static function defineEvents(): iterable;
}
