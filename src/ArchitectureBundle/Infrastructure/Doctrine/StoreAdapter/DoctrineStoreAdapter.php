<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Infrastructure\Doctrine\StoreAdapter;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\DBAL\Exception as DbalException;
use SixtyEightPublishers\ArchitectureBundle\Infrastructure\Common\Exception\TransactionException;
use SixtyEightPublishers\ArchitectureBundle\Infrastructure\Common\StoreAdapter\StoreAdapterInterface;

final class DoctrineStoreAdapter implements StoreAdapterInterface
{
	protected ManagerRegistry $managerRegistry;

	protected ?string $entityManagerName;

	/**
	 * @param \Doctrine\Persistence\ManagerRegistry $managerRegistry
	 * @param string|NULL                           $entityManagerName
	 */
	public function __construct(ManagerRegistry $managerRegistry, ?string $entityManagerName = NULL)
	{
		$this->managerRegistry = $managerRegistry;
		$this->entityManagerName = $entityManagerName;
	}

	/**
	 * {@inheritDoc}
	 */
	public function supportsTransactions(): bool
	{
		return TRUE;
	}

	/**
	 * {@inheritDoc}
	 */
	public function beginTransaction(): void
	{
		try {
			$this->resolveEntityManager()->getConnection()->beginTransaction();
		} catch (DbalException $e) {
			throw TransactionException::unableToBeginTransaction($e->getMessage());
		}
	}

	/**
	 * {@inheritDoc}
	 */
	public function commitTransaction(): void
	{
		try {
			$em = $this->resolveEntityManager();

			$em->flush();
			$em->getConnection()->commit();
		} catch (DbalException $e) {
			throw TransactionException::unableToCommitTransaction($e->getMessage());
		}
	}

	/**
	 * {@inheritDoc}
	 */
	public function rollbackTransaction(): void
	{
		try {
			$this->resolveEntityManager()->getConnection()->rollBack();
		} catch (DbalException $e) {
			throw TransactionException::unableToRollbackTransaction($e->getMessage());
		}
	}

	/**
	 * {@inheritDoc}
	 */
	public function hasActiveTransaction(): bool
	{
		return $this->resolveEntityManager()->getConnection()->isTransactionActive();
	}

	/**
	 * {@inheritDoc}
	 *
	 * @throws \Doctrine\DBAL\Exception
	 */
	public function pingConnection(): void
	{
		$em = $this->resolveEntityManager();
		$connection = $em->getConnection();

		try {
			$connection->executeQuery($connection->getDatabasePlatform()->getDummySelectSQL());
		} catch (DBALException $e) {
			$connection->close();
			$connection->connect();
		}

		if (!$em->isOpen()) {
			$this->managerRegistry->resetManager($this->entityManagerName);
		}
	}

	/**
	 * {@inheritDoc}
	 */
	public function closeConnection(): void
	{
		$this->resolveEntityManager()->getConnection()->close();
	}

	/**
	 * @return \Doctrine\ORM\EntityManagerInterface
	 */
	private function resolveEntityManager(): EntityManagerInterface
	{
		return $this->managerRegistry->getManager($this->entityManagerName);
	}
}
