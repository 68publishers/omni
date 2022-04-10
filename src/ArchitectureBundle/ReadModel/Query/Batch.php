<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\ReadModel\Query;

use Countable;
use ArrayIterator;
use IteratorAggregate;

final class Batch implements Countable, IteratorAggregate
{
	private int $batchSize;

	private int $offset;

	private int $totalCount;

	private array $results;

	private function __construct()
	{
	}

	/**
	 * @param int   $batchSize
	 * @param int   $offset
	 * @param int   $totalCount
	 * @param array $results
	 *
	 * @return static
	 */
	public static function create(int $batchSize, int $offset, int $totalCount, array $results): self
	{
		$batch = new self();
		$batch->batchSize = $batchSize;
		$batch->offset = $offset;
		$batch->totalCount = $totalCount;
		$batch->results = $results;

		return $batch;
	}

	/**
	 * @return int
	 */
	public function batchSize(): int
	{
		return $this->batchSize;
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
