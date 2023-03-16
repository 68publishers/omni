<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\ReadModel\Query;

trait PaginatedQueryTrait
{
    private ?int $offset = null;

    private int $limit = 10;

    public function getOffset(): ?int
    {
        return $this->offset;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }

    public function withSize(int $limit, ?int $offset): static
    {
        $query = clone $this;
        $query->limit = $limit;
        $query->offset = $offset;

        return $query;
    }
}
