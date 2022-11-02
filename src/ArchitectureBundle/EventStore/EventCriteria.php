<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\EventStore;

use DateTimeImmutable;
use SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\AggregateId;

final class EventCriteria
{
	public const SORTING_FROM_OLDEST = 'oldest';
	public const SORTING_FROM_NEWEST = 'newest';
	public const SORTING_FROM_LOWEST_POSITION= 'lowest_position';
	public const SORTING_FROM_HIGHEST_POSITION= 'highest_position';

	private string $aggregateRootClassname;

	private ?AggregateId $aggregateId = NULL;

	private ?DateTimeImmutable $createdBefore = NULL;

	private ?DateTimeImmutable $createdAfter = NULL;

	private ?string $positionGreaterThan = NULL;

	private ?string $positionLessThan = NULL;

	private array $eventNames = [];

	private string $sorting = self::SORTING_FROM_NEWEST;

	private ?int $limit = NULL;

	private ?int $offset = NULL;

	private function __construct()
	{
	}

	/**
	 * @param string $aggregateRootClassname
	 *
	 * @return static
	 */
	public static function create(string $aggregateRootClassname): self
	{
		$criteria = new self();
		$criteria->aggregateRootClassname = $aggregateRootClassname;

		return $criteria;
	}

	/**
	 * @param \SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\AggregateId $aggregateId
	 *
	 * @return $this
	 */
	public function withAggregateId(AggregateId $aggregateId): self
	{
		$criteria = clone $this;
		$criteria->aggregateId = $aggregateId;

		return $criteria;
	}

	/**
	 * @param \DateTimeImmutable $createdBefore
	 *
	 * @return $this
	 */
	public function withCreatedBefore(DateTimeImmutable $createdBefore): self
	{
		$criteria = clone $this;
		$criteria->createdBefore = $createdBefore;

		return $criteria;
	}

	/**
	 * @param \DateTimeImmutable $createdAfter
	 *
	 * @return $this
	 */
	public function withCreatedAfter(DateTimeImmutable $createdAfter): self
	{
		$criteria = clone $this;
		$criteria->createdAfter = $createdAfter;

		return $criteria;
	}

	/**
	 * @param string $position
	 *
	 * @return $this
	 */
	public function withPositionGreaterThan(string $position): self
	{
		$criteria = clone $this;
		$criteria->positionGreaterThan = $position;

		return $criteria;
	}

	/**
	 * @param string $position
	 *
	 * @return $this
	 */
	public function withPositionLessThan(string $position): self
	{
		$criteria = clone $this;
		$criteria->positionLessThan = $position;

		return $criteria;
	}

	/**
	 * @param array<string> $eventNames
	 *
	 * @return $this
	 */
	public function withEventNames(array $eventNames): self
	{
		$criteria = clone $this;
		$criteria->eventNames = $eventNames;

		return $criteria;
	}

	/**
	 * @return $this
	 */
	public function withNewestSorting(): self
	{
		$criteria = clone $this;
		$criteria->sorting = self::SORTING_FROM_NEWEST;

		return $criteria;
	}

	/**
	 * @return $this
	 */
	public function withOldestSorting(): self
	{
		$criteria = clone $this;
		$criteria->sorting = self::SORTING_FROM_OLDEST;

		return $criteria;
	}

	/**
	 * @return $this
	 */
	public function withLowestPositionSorting(): self
	{
		$criteria = clone $this;
		$criteria->sorting = self::SORTING_FROM_LOWEST_POSITION;

		return $criteria;
	}

	/**
	 * @return $this
	 */
	public function withHighestPositionSorting(): self
	{
		$criteria = clone $this;
		$criteria->sorting = self::SORTING_FROM_HIGHEST_POSITION;

		return $criteria;
	}

	/**
	 * @param int|NULL $limit
	 * @param int|NULL $offset
	 *
	 * @return $this
	 */
	public function withSize(?int $limit, ?int $offset): self
	{
		$criteria = clone $this;
		$criteria->limit = $limit;
		$criteria->offset = $offset;

		return $criteria;
	}

	/**
	 * @return string
	 */
	public function aggregateRootClassname(): string
	{
		return $this->aggregateRootClassname;
	}

	/**
	 * @return \SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\AggregateId|NULL
	 */
	public function aggregateId(): ?AggregateId
	{
		return $this->aggregateId;
	}

	/**
	 * @return \DateTimeImmutable|NULL
	 */
	public function createdBefore(): ?DateTimeImmutable
	{
		return $this->createdBefore;
	}

	/**
	 * @return \DateTimeImmutable|NULL
	 */
	public function createdAfter(): ?DateTimeImmutable
	{
		return $this->createdAfter;
	}

	/**
	 * @return string|NULL
	 */
	public function positionGreaterThan(): ?string
	{
		return $this->positionGreaterThan;
	}

	/**
	 * @return string|NULL
	 */
	public function positionLessThan(): ?string
	{
		return $this->positionLessThan;
	}

	/**
	 * @return array<string>
	 */
	public function eventNames(): array
	{
		return $this->eventNames;
	}

	/**
	 * @return string
	 */
	public function sorting(): string
	{
		return $this->sorting;
	}

	/**
	 * @return int|NULL
	 */
	public function limit(): ?int
	{
		return $this->limit;
	}

	/**
	 * @return int|NULL
	 */
	public function offset(): ?int
	{
		return $this->offset;
	}
}
