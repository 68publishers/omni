<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Infrastructure\Doctrine;

use Generator;
use Doctrine\ORM\Query;
use Doctrine\ORM\Tools\Pagination\Paginator;
use SixtyEightPublishers\ArchitectureBundle\ReadModel\Query\Batch;
use SixtyEightPublishers\ArchitectureBundle\ReadModel\Query\BatchUtils;
use SixtyEightPublishers\ArchitectureBundle\ReadModel\Query\BatchedQueryInterface;

final class BatchGeneratorFactory
{
	private function __construct()
	{
	}

	/**
	 * @param \SixtyEightPublishers\ArchitectureBundle\ReadModel\Query\BatchedQueryInterface $batchedQuery
	 * @param \Doctrine\ORM\Query                                                            $query
	 * @param callable                                                                       $mapper
	 * @param bool                                                                           $fetchJoinCollection
	 *
	 * @return \Generator
	 */
	public static function create(BatchedQueryInterface $batchedQuery, Query $query, callable $mapper, bool $fetchJoinCollection = FALSE): Generator
	{
		$paginator = new Paginator($query, $fetchJoinCollection);
		$totalCount = count($paginator);

		foreach (BatchUtils::from($totalCount, $batchedQuery->batchSize()) as [$limit, $offset]) {
			$paginator->getQuery()
				->setMaxResults($limit)
				->setFirstResult($batchedQuery->staticOffset() ?? $offset);

			$results = [];

			foreach ($paginator as $item) {
				$results[] = $mapper($item);
			}

			yield Batch::create(
				$batchedQuery->batchSize(),
				$batchedQuery->staticOffset() ?? $offset,
				$totalCount,
				$results
			);
		}
	}
}
