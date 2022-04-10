<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Domain\Exception;

use DomainException;
use SixtyEightPublishers\ArchitectureBundle\Domain\Dto\AggregateId;

final class UnableToRecordEventOnDeletedAggregateException extends DomainException
{
	private string $aggregateClassname;

	private AggregateId $aggregateId;

	/**
	 * @param string                                                          $message
	 * @param string                                                          $aggregateClassname
	 * @param \SixtyEightPublishers\ArchitectureBundle\Domain\Dto\AggregateId $aggregateId
	 */
	private function __construct(string $message, string $aggregateClassname, AggregateId $aggregateId)
	{
		parent::__construct($message);

		$this->aggregateClassname = $aggregateClassname;
		$this->aggregateId = $aggregateId;
	}

	/**
	 * @param string                                                          $aggregateClassname
	 * @param \SixtyEightPublishers\ArchitectureBundle\Domain\Dto\AggregateId $aggregateId
	 *
	 * @return $this
	 */
	public static function create(string $aggregateClassname, AggregateId $aggregateId): self
	{
		return new self(sprintf(
			'Unable to record event on deleted aggregate %s of type %s',
			$aggregateId->toString(),
			$aggregateClassname
		), $aggregateClassname, $aggregateId);
	}

	/**
	 * @return string
	 */
	public function getAggregateClassname(): string
	{
		return $this->aggregateClassname;
	}

	/**
	 * @return \SixtyEightPublishers\ArchitectureBundle\Domain\Dto\AggregateId
	 */
	public function getAggregateId(): AggregateId
	{
		return $this->aggregateId;
	}
}
