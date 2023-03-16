<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Infrastructure\Common\PersistenceAdapter;

use SixtyEightPublishers\ArchitectureBundle\Infrastructure\Common\Exception\TransactionException;

abstract class AbstractNonTransactionalPersistenceAdapter implements PersistenceAdapterInterface
{
    public function supportsTransactions(): bool
    {
        return false;
    }

    public function beginTransaction(): void
    {
        throw TransactionException::unableToBeginTransaction('The storage is non transactional.');
    }

    public function commitTransaction(): void
    {
        throw TransactionException::unableToCommitTransaction('The storage is non transactional.');
    }

    public function rollbackTransaction(): void
    {
        throw TransactionException::unableToRollbackTransaction('The storage is non transactional.');
    }

    public function hasActiveTransaction(): bool
    {
        return false;
    }
}
