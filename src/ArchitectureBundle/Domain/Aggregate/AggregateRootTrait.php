<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Domain\Aggregate;

use RuntimeException;
use SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\AggregateId;
use SixtyEightPublishers\ArchitectureBundle\Domain\Event\AbstractDomainEvent;

trait AggregateRootTrait
{
	protected int $version = 0;

	/** @var \SixtyEightPublishers\ArchitectureBundle\Domain\Event\AbstractDomainEvent[]  */
	protected array $recordedEvents = [];

	/**
	 * @return \SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\AggregateId
	 */
	abstract public function aggregateId(): AggregateId;

	/**
	 * @return int
	 */
	public function version(): int
	{
		return $this->version;
	}

	/**
	 * @return \SixtyEightPublishers\ArchitectureBundle\Domain\Event\AbstractDomainEvent[]
	 */
	public function popRecordedEvents(): array
	{
		$pendingEvents = $this->recordedEvents;
		$this->recordedEvents = [];

		return $pendingEvents;
	}

	/**
	 * @param \SixtyEightPublishers\ArchitectureBundle\Domain\Event\AbstractDomainEvent $event
	 *
	 * @return void
	 */
	protected function apply(AbstractDomainEvent $event): void
	{
		$handler = $this->determineEventHandlerMethodFor($event);

		if (!method_exists($this, $handler)) {
			throw new RuntimeException(sprintf(
				'Missing event handler method %s for aggregate root %s',
				$handler,
				get_class($this)
			));
		}

		$this->{$handler}($event);
	}

	/**
	 * @param \SixtyEightPublishers\ArchitectureBundle\Domain\Event\AbstractDomainEvent $event
	 *
	 * @return void
	 */
	protected function recordThat(AbstractDomainEvent $event): void
	{
		++$this->version;
		$this->recordedEvents[] = $event->withVersion($this->version);

		$this->apply($event);
	}

	/**
	 * @param \SixtyEightPublishers\ArchitectureBundle\Domain\Event\AbstractDomainEvent $event
	 *
	 * @return string
	 */
	protected function determineEventHandlerMethodFor(AbstractDomainEvent $event): string
	{
		return 'when' . implode(array_slice(explode('\\', get_class($event)), -1));
	}
}
