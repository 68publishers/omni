<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\ReadModel\Query;

interface PaginatedQueryInterface extends QueryInterface
{
	/**
	 * @return int|NULL
	 */
	public function offset(): ?int;

	/**
	 * @return int
	 */
	public function limit(): int;

	/**
	 * @param int      $limit
	 * @param int|NULL $offset
	 *
	 * @return $this
	 */
	public function withSize(int $limit, ?int $offset): self;
}
