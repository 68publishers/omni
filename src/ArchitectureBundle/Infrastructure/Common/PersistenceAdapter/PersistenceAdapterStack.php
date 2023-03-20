<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Infrastructure\Common\PersistenceAdapter;

final class PersistenceAdapterStack implements PersistenceAdapterInterface
{
    /**
     * @param array<PersistenceAdapterInterface> $persistenceAdapters
     */
    public function __construct(
        private readonly array $persistenceAdapters = [],
    ) {}

    public function supportsTransactions(): bool
    {
        foreach ($this->persistenceAdapters as $persistenceAdapter) {
            if ($persistenceAdapter->supportsTransactions()) {
                return true;
            }
        }

        return false;
    }

    public function beginTransaction(): void
    {
        foreach ($this->persistenceAdapters as $persistenceAdapter) {
            if ($persistenceAdapter->supportsTransactions()) {
                $persistenceAdapter->beginTransaction();
            }
        }
    }

    public function commitTransaction(): void
    {
        foreach ($this->persistenceAdapters as $persistenceAdapter) {
            if ($persistenceAdapter->supportsTransactions()) {
                $persistenceAdapter->commitTransaction();
            }
        }
    }

    public function rollbackTransaction(): void
    {
        foreach ($this->persistenceAdapters as $persistenceAdapter) {
            if ($persistenceAdapter->supportsTransactions()) {
                $persistenceAdapter->rollbackTransaction();
            }
        }
    }

    public function hasActiveTransaction(): bool
    {
        foreach ($this->persistenceAdapters as $persistenceAdapter) {
            if ($persistenceAdapter->supportsTransactions() && $persistenceAdapter->hasActiveTransaction()) {
                return true;
            }
        }

        return false;
    }

    public function pingConnection(): void
    {
        foreach ($this->persistenceAdapters as $persistenceAdapter) {
            $persistenceAdapter->pingConnection();
        }
    }

    public function closeConnection(): void
    {
        foreach ($this->persistenceAdapters as $persistenceAdapter) {
            $persistenceAdapter->closeConnection();
        }
    }
}
