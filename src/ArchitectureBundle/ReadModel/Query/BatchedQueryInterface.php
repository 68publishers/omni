<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\ReadModel\Query;

interface BatchedQueryInterface extends QueryInterface
{
    /**
     * Size of a batch
     */
    public function getBatchSize(): int;

    /**
     * Static offset for queries e.g. LIMIT ... OFFSET <static-offset>. Useful when you are iterating over batches and deleting the entities.
     */
    public function getStaticOffset(): ?int;

    public function withBatchSize(int $batchSize): static;

    public function withStaticOffset(?int $staticOffset): static;
}
