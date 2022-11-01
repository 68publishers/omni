<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ProjectionBundle\Projection;

final class EventDefinition
{
	public string $aggregateRootClassname;

	public string $eventClassname;

	public ?string $methodName;

	public function __construct(string $aggregateRootClassname, string $eventClassname, ?string $methodName = NULL)
	{
		$this->aggregateRootClassname = $aggregateRootClassname;
		$this->eventClassname = $eventClassname;
		$this->methodName = $methodName;
	}
}
