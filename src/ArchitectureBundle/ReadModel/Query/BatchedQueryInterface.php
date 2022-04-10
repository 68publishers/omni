<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\ReadModel\Query;

interface BatchedQueryInterface extends QueryInterface
{
	/**
	 * Size of a batch
	 *
	 * @return int
	 */
	public function batchSize(): int;

	/**
	 * Static offset for queries e.g. LIMIT ... OFFSET <static-offset>. Useful when you are iterating over batches and deleting the entities.
	 *
	 * @return int
	 */
	public function staticOffset(): ?int;

	/**
	 * @param int $batchSize
	 *
	 * @return $this
	 */
	public function withBatchSize(int $batchSize): self;

	/**
	 * @param int|NULL $staticOffset
	 *
	 * @return $this
	 */
	public function withStaticOffset(?int $staticOffset): self;
}
