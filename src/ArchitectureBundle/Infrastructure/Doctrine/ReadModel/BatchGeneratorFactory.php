<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Infrastructure\Doctrine\ReadModel;

use Generator;
use Doctrine\ORM\Query;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\Tools\Pagination\Paginator;
use SixtyEightPublishers\ArchitectureBundle\ReadModel\Query\Batch;
use SixtyEightPublishers\ArchitectureBundle\ReadModel\Query\BatchUtils;
use SixtyEightPublishers\ArchitectureBundle\ReadModel\View\ViewFactoryInterface;
use SixtyEightPublishers\ArchitectureBundle\ReadModel\Query\BatchedQueryInterface;

final class BatchGeneratorFactory
{
	private ViewFactoryInterface $viewFactory;

	/**
	 * @param \SixtyEightPublishers\ArchitectureBundle\ReadModel\View\ViewFactoryInterface $viewFactory
	 */
	public function __construct(ViewFactoryInterface $viewFactory)
	{
		$this->viewFactory = $viewFactory;
	}

	/**
	 * @param \SixtyEightPublishers\ArchitectureBundle\ReadModel\Query\BatchedQueryInterface $batchedQuery
	 * @param \Doctrine\ORM\Query                                                            $query
	 * @param string                                                                         $viewClassname
	 * @param bool                                                                           $fetchJoinCollection
	 *
	 * @return \Generator
	 */
	public function create(BatchedQueryInterface $batchedQuery, Query $query, string $viewClassname, bool $fetchJoinCollection = FALSE): Generator
	{
		$query->setHydrationMode(AbstractQuery::HYDRATE_ARRAY);

		$paginator = new Paginator($query, $fetchJoinCollection);
		$totalCount = count($paginator);

		foreach (BatchUtils::from($totalCount, $batchedQuery->batchSize()) as [$limit, $offset]) {
			$paginator->getQuery()
				->setMaxResults($limit)
				->setFirstResult($batchedQuery->staticOffset() ?? $offset);

			$results = [];

			foreach ($paginator as $item) {
				$results[] = $this->viewFactory->create($viewClassname, DoctrineViewData::create($item));
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
