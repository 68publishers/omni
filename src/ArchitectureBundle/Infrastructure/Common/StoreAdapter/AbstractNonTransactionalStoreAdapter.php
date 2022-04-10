<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Infrastructure\Common\StoreAdapter;

use SixtyEightPublishers\ArchitectureBundle\Infrastructure\Common\Exception\TransactionException;

abstract class AbstractNonTransactionalStoreAdapter implements StoreAdapterInterface
{
	/**
	 * {@inheritDoc}
	 */
	public function supportsTransactions(): bool
	{
		return FALSE;
	}

	/**
	 * {@inheritDoc}
	 */
	public function beginTransaction(): void
	{
		throw TransactionException::unableToBeginTransaction('The storage is non transactional.');
	}

	/**
	 * {@inheritDoc}
	 */
	public function commitTransaction(): void
	{
		throw TransactionException::unableToCommitTransaction('The storage is non transactional.');
	}

	/**
	 * {@inheritDoc}
	 */
	public function rollbackTransaction(): void
	{
		throw TransactionException::unableToRollbackTransaction('The storage is non transactional.');
	}

	/**
	 * {@inheritDoc}
	 */
	public function hasActiveTransaction(): bool
	{
		return FALSE;
	}
}
