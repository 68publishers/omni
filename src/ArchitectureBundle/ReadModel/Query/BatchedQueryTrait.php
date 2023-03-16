<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\ReadModel\Query;

trait BatchedQueryTrait
{
    private int $batchSize = 50;

    private ?int $staticOffset = null;

    public function getBatchSize(): int
    {
        return $this->batchSize;
    }

    public function getStaticOffset(): ?int
    {
        return $this->staticOffset;
    }

    public function withBatchSize(int $batchSize): static
    {
        $query = clone $this;
        $query->batchSize = $batchSize;

        return $query;
    }

    public function withStaticOffset(?int $staticOffset): static
    {
        $query = clone $this;
        $query->staticOffset = $staticOffset;

        return $query;
    }
}
