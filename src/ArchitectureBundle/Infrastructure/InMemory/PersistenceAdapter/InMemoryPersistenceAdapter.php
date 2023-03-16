<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Infrastructure\InMemory\PersistenceAdapter;

use SixtyEightPublishers\ArchitectureBundle\Infrastructure\Common\PersistenceAdapter\AbstractNonTransactionalPersistenceAdapter;

final class InMemoryPersistenceAdapter extends AbstractNonTransactionalPersistenceAdapter
{
    public function pingConnection(): void
    {
    }

    public function closeConnection(): void
    {
    }
}
