<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\ReadModel\Query;

use Countable;
use ArrayIterator;
use IteratorAggregate;

final class PaginatedResult implements Countable, IteratorAggregate
{
	private int $offset;

	private int $limit;

	private int $totalCount;

	private array $results;

	private function __construct()
	{
	}

	/**
	 * @param int   $offset
	 * @param int   $limit
	 * @param int   $totalCount
	 * @param array $results
	 *
	 * @return static
	 */
	public static function create(int $offset, int $limit, int $totalCount, array $results): self
	{
		$result = new self();
		$result->offset = $offset;
		$result->limit = $limit;
		$result->totalCount = $totalCount;
		$result->results = $results;

		return $result;
	}

	/**
	 * @return int
	 */
	public function offset(): int
	{
		return $this->offset;
	}

	/**
	 * @return int
	 */
	public function limit(): int
	{
		return $this->limit;
	}

	/**
	 * @return int
	 */
	public function totalCount(): int
	{
		return $this->totalCount;
	}

	/**
	 * @return array
	 */
	public function results(): array
	{
		return $this->results;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getIterator(): ArrayIterator
	{
		return new ArrayIterator($this->results());
	}

	/**
	 * {@inheritDoc}
	 */
	public function count(): int
	{
		return count($this->results());
	}
}
