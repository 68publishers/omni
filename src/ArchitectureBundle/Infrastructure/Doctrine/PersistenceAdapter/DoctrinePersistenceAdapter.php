<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Infrastructure\Doctrine\PersistenceAdapter;

use Doctrine\DBAL\Exception as DbalException;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use SixtyEightPublishers\ArchitectureBundle\Infrastructure\Common\Exception\TransactionException;
use SixtyEightPublishers\ArchitectureBundle\Infrastructure\Common\PersistenceAdapter\PersistenceAdapterInterface;
use function assert;

final class DoctrinePersistenceAdapter implements PersistenceAdapterInterface
{
    public function __construct(
        private readonly ManagerRegistry $managerRegistry,
        private readonly ?string $entityManagerName = null,
    ) {}

    public function supportsTransactions(): bool
    {
        return true;
    }

    public function beginTransaction(): void
    {
        try {
            $this->resolveEntityManager()->getConnection()->beginTransaction();
        } catch (DbalException $e) {
            throw TransactionException::unableToBeginTransaction($e->getMessage());
        }
    }

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

    public function rollbackTransaction(): void
    {
        try {
            $this->resolveEntityManager()->getConnection()->rollBack();
        } catch (DbalException $e) {
            throw TransactionException::unableToRollbackTransaction($e->getMessage());
        }
    }

    public function hasActiveTransaction(): bool
    {
        return $this->resolveEntityManager()->getConnection()->isTransactionActive();
    }

    /**
     * @throws DbalException
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

    public function closeConnection(): void
    {
        $this->resolveEntityManager()->getConnection()->close();
    }

    private function resolveEntityManager(): EntityManagerInterface
    {
        $em = $this->managerRegistry->getManager($this->entityManagerName);
        assert($em instanceof EntityManagerInterface);

        return $em;
    }
}
