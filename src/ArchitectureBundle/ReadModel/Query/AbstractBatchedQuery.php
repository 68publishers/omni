<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\ReadModel\Query;

abstract class AbstractBatchedQuery extends AbstractQuery implements BatchedQueryInterface
{
	/**
	 * {@inheritDoc}
	 */
	public function batchSize(): int
	{
		return $this->getParam('batch_size') ?? 50;
	}

	/**
	 * {@inheritDoc}
	 */
	public function staticOffset(): ?int
	{
		return $this->getParam('static_offset');
	}

	/**
	 * {@inheritDoc}
	 */
	public function withBatchSize(int $batchSize): self
	{
		return $this->withParam('batch_size', $batchSize);
	}

	/**
	 * {@inheritDoc}
	 */
	public function withStaticOffset(?int $staticOffset): self
	{
		return $this->withParam('static_offset', $staticOffset);
	}
}
