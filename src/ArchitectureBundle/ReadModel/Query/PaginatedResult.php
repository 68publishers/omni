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
final class PaginatedResult implements Countable, IteratorAggregate
{
    /**
     * @param array<int, object> $results
     */
    public function __construct(
        public readonly int $offset,
        public readonly int $limit,
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
            $this->offset,
            $this->limit,
            array_map($mapper, $this->results),
        );
    }
}
