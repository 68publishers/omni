<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\ReadModel\Query;

abstract class AbstractPaginatedQuery extends AbstractQuery implements PaginatedQueryInterface
{
	/**
	 * {@inheritDoc}
	 */
	public function offset(): ?int
	{
		return $this->getParam('offset');
	}

	/**
	 * {@inheritDoc}
	 */
	public function limit(): int
	{
		return $this->getParam('limit') ?? 10;
	}

	/**
	 * {@inheritDoc}
	 */
	public function withSize(int $limit, ?int $offset): self
	{
		return $this->withParam('limit', $limit)
			->withParam('offset', $offset);
	}
}
