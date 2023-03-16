<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\ReadModel\Query;

use ArrayIterator;
use Countable;
use IteratorAggregate;
use function array_map;
use function count;

/**
 * @implements IteratorAggregate<int, object>
 */
final class Batch implements Countable, IteratorAggregate
{
    /**
     * @param array<int, object> $results
     */
    public function __construct(
        public readonly int $batchSize,
        public readonly int $offset,
        public readonly int $totalCount,
        public readonly array $results,
    ) {}

    /**
     * @return ArrayIterator<int, object>
     */
    public function getIterator(): ArrayIterator
    {
        return new ArrayIterator($this->results);
    }

    public function count(): int
    {
        return count($this->results);
    }

    public function map(callable $mapper): self
    {
        return new self(
            $this->batchSize,
            $this->offset,
            $this->totalCount,
            array_map($mapper, $this->results),
        );
    }
}
