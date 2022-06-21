<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Domain\Aggregate;

use DateTimeImmutable;
use SixtyEightPublishers\ArchitectureBundle\Domain\Event\AggregateDeleted;
use SixtyEightPublishers\ArchitectureBundle\Domain\Event\AbstractDomainEvent;
use SixtyEightPublishers\ArchitectureBundle\Domain\Exception\UnableToRecordEventOnDeletedAggregateException;

trait DeletableAggregateRootTrait
{
	use AggregateRootTrait {
		recordThat as _recordThat;
	}

	protected ?DateTimeImmutable $deletedAt = NULL;

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
		return NULL !== $this->deletedAt;
	}

	/**
	 * @return void
	 */
	public function delete(): void
	{
		if (!$this->deletedAt) {
			$this->recordThat(AggregateDeleted::create(static::class, $this->aggregateId()));
		}
	}

	/**
	 * @param \SixtyEightPublishers\ArchitectureBundle\Domain\Event\AggregateDeleted $event
	 *
	 * @return void
	 */
	protected function whenAggregateDeleted(AggregateDeleted $event): void
	{
		$this->deletedAt = $event->createdAt();
	}
}
