<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Domain\Event;

use SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\AggregateId;

final class AggregateDeleted extends AbstractDomainEvent
{
	private string $aggregateClassname;

	/**
	 * @param string                                                                  $aggregateClassname
	 * @param \SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\AggregateId $aggregateId
	 *
	 * @return static
	 */
	public static function create(string $aggregateClassname, AggregateId $aggregateId): self
	{
		$event = self::occur($aggregateId->toString(), [
			'aggregate_class_name' => $aggregateClassname,
		]);

		$event->aggregateClassname = $aggregateClassname;

		return $event;
	}

	/**
	 * @return string
	 */
	public function aggregateClassname(): string
	{
		return $this->aggregateClassname;
	}

	/**
	 * {@inheritDoc}
	 */
	protected function reconstituteState(array $parameters): void
	{
		$this->aggregateClassname = $parameters['aggregate_class_name'];
	}
}
