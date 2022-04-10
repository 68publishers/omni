<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Infrastructure\Doctrine;

use Doctrine\ORM\Query;
use Doctrine\ORM\Tools\Pagination\Paginator;
use SixtyEightPublishers\ArchitectureBundle\ReadModel\Query\PaginatedResult;
use SixtyEightPublishers\ArchitectureBundle\ReadModel\Query\PaginatedQueryInterface;

final class PaginatedResultFactory
{
	private function __construct()
	{
	}

	/**
	 * @param \SixtyEightPublishers\ArchitectureBundle\ReadModel\Query\PaginatedQueryInterface $paginatedQuery
	 * @param \Doctrine\ORM\Query                                                              $query
	 * @param callable                                                                         $mapper
	 * @param bool                                                                             $fetchJoinCollection
	 *
	 * @return \SixtyEightPublishers\ArchitectureBundle\ReadModel\Query\PaginatedResult
	 */
	public static function create(PaginatedQueryInterface $paginatedQuery, Query $query, callable $mapper, bool $fetchJoinCollection = FALSE): PaginatedResult
	{
		$query = $query->setMaxResults($paginatedQuery->limit());

		if (NULL !== $paginatedQuery->offset()) {
			$query = $query->setFirstResult($paginatedQuery->offset());
		}

		$paginator = new Paginator($query, $fetchJoinCollection);

		$results = [];
		$totalCount = count($paginator);

		foreach ($paginator as $item) {
			$results[] = $mapper($item);
		}

		return PaginatedResult::create(
			$paginatedQuery->offset() ?? 0,
			$paginatedQuery->limit(),
			$totalCount,
			$results
		);
	}
}
