<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Infrastructure\Doctrine\ReadModel;

use Doctrine\ORM\Query;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Generator;
use SixtyEightPublishers\ArchitectureBundle\ReadModel\Query\Batch;
use SixtyEightPublishers\ArchitectureBundle\ReadModel\Query\BatchedQueryInterface;
use SixtyEightPublishers\ArchitectureBundle\ReadModel\Query\BatchUtils;
use SixtyEightPublishers\ArchitectureBundle\ReadModel\Query\PaginatedQueryInterface;
use SixtyEightPublishers\ArchitectureBundle\ReadModel\Query\PaginatedResult;
use function count;
use function iterator_to_array;

final class Helpers
{
    private function __construct() {}

    public static function createPaginatedResult(
        PaginatedQueryInterface $paginatedQuery,
        Query $query,
        bool $fetchJoinCollection = false,
        ?bool $useOutputWalkers = null,
        ?callable $mapper = null,
    ): PaginatedResult {
        $query = $query->setMaxResults($paginatedQuery->getLimit());

        if (null !== $paginatedQuery->getOffset()) {
            $query = $query->setFirstResult($paginatedQuery->getOffset());
        }

        $paginator = new Paginator($query, $fetchJoinCollection);
        $paginator->setUseOutputWalkers($useOutputWalkers);

        $result = new PaginatedResult(
            $paginatedQuery->getOffset() ?? 0,
            $paginatedQuery->getLimit(),
            iterator_to_array($paginator),
        );

        return $mapper ? $result->map($mapper) : $result;
    }

    public static function createBatchGenerator(
        BatchedQueryInterface $batchedQuery,
        Query $query,
        bool $fetchJoinCollection = false,
        ?bool $useOutputWalkers = null,
        ?callable $mapper = null,
    ): Generator {
        $paginator = new Paginator($query, $fetchJoinCollection);
        $paginator->setUseOutputWalkers($useOutputWalkers);

        $totalCount = count($paginator);

        foreach (BatchUtils::from($totalCount, $batchedQuery->getBatchSize()) as [$limit, $offset]) {
            $paginator->getQuery()
                ->setMaxResults($limit)
                ->setFirstResult($batchedQuery->getStaticOffset() ?? $offset);

            $batch = new Batch(
                $batchedQuery->getBatchSize(),
                $batchedQuery->getStaticOffset() ?? $offset,
                $totalCount,
                iterator_to_array($paginator),
            );

            yield $mapper ? $batch->map($mapper) : $batch;
        }
    }
}
