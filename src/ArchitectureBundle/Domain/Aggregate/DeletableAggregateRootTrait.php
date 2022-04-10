<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Domain\Aggregate;

use SixtyEightPublishers\ArchitectureBundle\Domain\Event\AggregateDeleted;
use SixtyEightPublishers\ArchitectureBundle\Domain\Event\AbstractDomainEvent;
use SixtyEightPublishers\ArchitectureBundle\Domain\Exception\UnableToRecordEventOnDeletedAggregateException;

trait DeletableAggregateRootTrait
{
	use AggregateRootTrait {
		recordThat as _recordThat;
	}

	private bool $deleted = FALSE;

	/**
	 * @param \SixtyEightPublishers\ArchitectureBundle\Domain\Event\AbstractDomainEvent $event
	 *
	 * @return void
	 */
	protected function recordThat(AbstractDomainEvent $event): void
	{
		if ($this->deleted()) {
			throw UnableToRecordEventOnDeletedAggregateException::create(static::class, $this->aggregateId());
		}

		$this->_recordThat($event);
	}

	/**
	 * @return bool
	 */
	public function deleted(): bool
	{
		return $this->deleted;
	}

	/**
	 * @return void
	 */
	public function delete(): void
	{
		if (!$this->deleted) {
			$this->recordThat(AggregateDeleted::create($this->aggregateId()));
		}
	}

	/**
	 * @param \SixtyEightPublishers\ArchitectureBundle\Domain\Event\AggregateDeleted $event
	 *
	 * @return void
	 */
	protected function whenAggregateDeleted(AggregateDeleted $event): void
	{
		$this->deleted = TRUE;
	}
}
