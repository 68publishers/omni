<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Infrastructure\Common\PersistenceAdapter;

use SixtyEightPublishers\ArchitectureBundle\Infrastructure\Common\Exception\TransactionException;

interface PersistenceAdapterInterface
{
    public function supportsTransactions(): bool;

    /**
     * @throws TransactionException
     */
    public function beginTransaction(): void;

    /**
     * @throws TransactionException
     */
    public function commitTransaction(): void;

    /**
     * @throws TransactionException
     */
    public function rollbackTransaction(): void;

    public function hasActiveTransaction(): bool;

    public function pingConnection(): void;

    public function closeConnection(): void;
}
