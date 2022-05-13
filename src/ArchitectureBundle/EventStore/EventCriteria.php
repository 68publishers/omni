<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\EventStore;

use DateTimeImmutable;
use SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\AggregateId;

final class EventCriteria
{
	public const SORTING_FROM_OLDEST = 'oldest';
	public const SORTING_FROM_NEWEST = 'newest';

	private string $aggregateRootClassname;

	private ?AggregateId $aggregateId = NULL;

	private ?DateTimeImmutable $createdBefore = NULL;

	private ?DateTimeImmutable $createdAfter = NULL;

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
