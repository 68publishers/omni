<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Infrastructure\Doctrine\ReadModel;

use Doctrine\ORM\Query;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\Tools\Pagination\Paginator;
use SixtyEightPublishers\ArchitectureBundle\ReadModel\Query\PaginatedResult;
use SixtyEightPublishers\ArchitectureBundle\ReadModel\View\ViewFactoryInterface;
use SixtyEightPublishers\ArchitectureBundle\ReadModel\Query\PaginatedQueryInterface;

final class PaginatedResultFactory
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
	 * @param \SixtyEightPublishers\ArchitectureBundle\ReadModel\Query\PaginatedQueryInterface $paginatedQuery
	 * @param \Doctrine\ORM\Query                                                              $query
	 * @param string                                                                           $viewClassname
	 * @param bool                                                                             $fetchJoinCollection
	 *
	 * @return \SixtyEightPublishers\ArchitectureBundle\ReadModel\Query\PaginatedResult
	 */
	public function create(PaginatedQueryInterface $paginatedQuery, Query $query, string $viewClassname, bool $fetchJoinCollection = FALSE): PaginatedResult
	{
		$query = $query
			->setHydrationMode(AbstractQuery::HYDRATE_ARRAY)
			->setMaxResults($paginatedQuery->limit());

		if (NULL !== $paginatedQuery->offset()) {
			$query = $query->setFirstResult($paginatedQuery->offset());
		}

		$paginator = new Paginator($query, $fetchJoinCollection);

		$results = [];

		foreach ($paginator as $item) {
			$results[] = $this->viewFactory->create($viewClassname, DoctrineViewData::create($item));
		}

		return PaginatedResult::create(
			$paginatedQuery->offset() ?? 0,
			$paginatedQuery->limit(),
			$results
		);
	}
}
