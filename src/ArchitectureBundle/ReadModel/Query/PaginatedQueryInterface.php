<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\ReadModel\Query;

interface PaginatedQueryInterface extends QueryInterface
{
    public function getOffset(): ?int;

    public function getLimit(): int;

    public function withSize(int $limit, ?int $offset): static;
}
