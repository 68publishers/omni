<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Infrastructure\Common\StoreAdapter;

interface StoreAdapterInterface
{
	/**
	 * @return bool
	 */
	public function supportsTransactions(): bool;

	/**
	 * @return void
	 * @throws \SixtyEightPublishers\ArchitectureBundle\Infrastructure\Common\Exception\TransactionException
	 */
	public function beginTransaction(): void;

	/**
	 * @return void
	 * @throws \SixtyEightPublishers\ArchitectureBundle\Infrastructure\Common\Exception\TransactionException
	 */
	public function commitTransaction(): void;

	/**
	 * @return void
	 * @throws \SixtyEightPublishers\ArchitectureBundle\Infrastructure\Common\Exception\TransactionException
	 */
	public function rollbackTransaction(): void;

	/**
	 * @return bool
	 */
	public function hasActiveTransaction(): bool;

	/**
	 * @return void
	 */
	public function pingConnection(): void;

	/**
	 * @return void
	 */
	public function closeConnection(): void;
}
