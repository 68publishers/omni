<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Infrastructure\Doctrine\EventStore;

use Doctrine\DBAL\ArrayParameterType;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception as DbalException;
use Doctrine\DBAL\Exception\RetryableException;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\EntityManagerInterface;
use SixtyEightPublishers\ArchitectureBundle\Domain\Event\AbstractDomainEvent;
use SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\EventId;
use SixtyEightPublishers\ArchitectureBundle\EventStore\EventCriteria;
use SixtyEightPublishers\ArchitectureBundle\EventStore\EventStoreException;
use SixtyEightPublishers\ArchitectureBundle\EventStore\EventStoreInterface;
use function assert;
use function is_string;
use function is_subclass_of;
use function sprintf;
use function trim;

final class DoctrineEventStore implements EventStoreInterface
{
    public const NAME = 'doctrine';

    public function __construct(
        private readonly EntityManagerInterface $em,
    ) {}

    public function store(string $aggregateRootClassname, array $events): void
    {
        $connection = $this->em->getConnection();
        $tableName = $this->getTableName($aggregateRootClassname);

        foreach ($events as $event) {
            try {
                $connection->insert($tableName, [
                    'event_id' => $event->getEventId()->toNative(),
                    'aggregate_id' => $event->getAggregateId()->toNative(),
                    'event_name' => $event->getEventName(),
                    'created_at' => $event->getCreatedAt(),
                    'parameters' => $event->getParameters(),
                    'metadata' => $event->getMetadata(),
                ], [
                    Types::GUID,
                    Types::GUID,
                    Types::STRING,
                    Types::DATETIME_IMMUTABLE,
                    Types::JSON,
                    Types::JSON,
                ]);
            } catch (DbalException $e) {
                throw EventStoreException::unableToStoreEvent($aggregateRootClassname, $event, $e instanceof RetryableException, $e);
            }
        }
    }

    public function get(string $aggregateRootClassname, EventId $eventId): ?AbstractDomainEvent
    {
        $connection = $this->em->getConnection();
        $tableName = $this->getTableName($aggregateRootClassname);

        $query = sprintf(
            'SELECT * FROM %s WHERE event_id = ? LIMIT 1;',
            $tableName,
        );

        try {
            $statement = $connection->executeQuery($query, [$eventId->toNative()], [Types::GUID]);
            $data = $statement->fetchAssociative();

            if (!$data) {
                return null;
            }

            return $this->hydrateEvent($connection, $data);
        } catch (DbalException $e) {
            throw EventStoreException::unableToGetEvent($aggregateRootClassname, $eventId, $e instanceof RetryableException, $e);
        }
    }

    public function find(EventCriteria $criteria): array
    {
        $connection = $this->em->getConnection();
        $tableName = $this->getTableName($criteria->getAggregateRootClassname());

        $qb = $connection->createQueryBuilder()
            ->select('*')
            ->from($tableName);

        $qb = $this->buildConditions($qb, $criteria);

        if (null !== $criteria->getLimit()) {
            $qb->setMaxResults($criteria->getLimit());
        }

        if (null !== $criteria->getOffset()) {
            $qb->setFirstResult($criteria->getOffset());
        }

        switch ($criteria->getSorting()) {
            case $criteria::SORTING_FROM_OLDEST:
                $qb->orderBy('created_at', 'ASC')
                    ->addOrderBy('id', 'ASC');

                break;
            case $criteria::SORTING_FROM_NEWEST:
                $qb->orderBy('created_at', 'DESC')
                    ->addOrderBy('id', 'DESC');

                break;
            case $criteria::SORTING_FROM_LOWEST_POSITION:
                $qb->orderBy('id', 'ASC');

                break;
            case $criteria::SORTING_FROM_HIGHEST_POSITION:
                $qb->orderBy('id', 'DESC');

                break;
        }

        try {
            $statement = $qb->executeQuery();
            $events = [];

            while (false !== ($data = $statement->fetchAssociative())) {
                $events[] = $this->hydrateEvent($connection, $data);
            }
        } catch (DbalException $e) {
            throw EventStoreException::unableToFindEvents($criteria->getAggregateRootClassname(), $e instanceof RetryableException, $e);
        }

        return $events;
    }

    public function count(EventCriteria $criteria): int
    {
        $connection = $this->em->getConnection();
        $tableName = $this->getTableName($criteria->getAggregateRootClassname());

        $qb = $connection->createQueryBuilder()
            ->select('COUNT(id)')
            ->from($tableName)
            ->setMaxResults(1);

        $qb = $this->buildConditions($qb, $criteria);

        try {
            return (int) $qb->executeQuery()->fetchOne();
        } catch (DbalException $e) {
            throw EventStoreException::unableToCountEvents($criteria->getAggregateRootClassname(), $e instanceof RetryableException, $e);
        }
    }

    private function buildConditions(QueryBuilder $qb, EventCriteria $criteria): QueryBuilder
    {
        if (null !== $criteria->getAggregateId()) {
            $qb->andWhere('aggregate_id = :aggregateId')
                ->setParameter('aggregateId', $criteria->getAggregateId()->toNative());
        }

        if (null !== $criteria->getCreatedBefore()) {
            $qb->andWhere('created_at <= :beforeDateTime')
                ->setParameter('beforeDateTime', $criteria->getCreatedBefore(), Types::DATETIME_IMMUTABLE);
        }

        if (null !== $criteria->getCreatedAfter()) {
            $qb->andWhere('created_at >= :afterDateTime')
                ->setParameter('afterDateTime', $criteria->getCreatedAfter(), Types::DATETIME_IMMUTABLE);
        }

        if (null !== $criteria->getPositionGreaterThan()) {
            $qb->andWhere('id > :positionGreaterThan')
                ->setParameter('positionGreaterThan', $criteria->getPositionGreaterThan(), Types::BIGINT);
        }

        if (null !== $criteria->getPositionLessThan()) {
            $qb->andWhere('id < :positionLessThan')
                ->setParameter('positionLessThan', $criteria->getPositionLessThan(), Types::BIGINT);
        }

        if (!empty($criteria->getEventNames())) {
            $qb->andWhere('event_name IN (:eventNames)')
                ->setParameter('eventNames', $criteria->getEventNames(), ArrayParameterType::STRING);
        }

        return $qb;
    }

    /**
     * @throws EventStoreException
     */
    private function getTableName(string $aggregateRootClassname): string
    {
        $classMetadata = $this->em->getClassMetadata($aggregateRootClassname);
        $tableName = $classMetadata->getTableName();

        try {
            $quoteCharacter = $this->em->getConnection()->getDatabasePlatform()->getIdentifierQuoteCharacter();
        } catch (DbalException $e) {
            throw EventStoreException::of($e, $e instanceof RetryableException);
        }

        $tableName = trim($tableName, $quoteCharacter);

        return $tableName . '_event_stream';
    }

    /**
     * @param  array<string, mixed> $data
     * @throws DbalException
     */
    private function hydrateEvent(Connection $connection, array $data): AbstractDomainEvent
    {
        $eventClassname = $data['event_name'];
        assert(is_string($eventClassname) && is_subclass_of($eventClassname, AbstractDomainEvent::class, true));

        return $eventClassname::reconstitute(
            $eventClassname,
            $connection->convertToPHPValue($data['event_id'], EventId::class),
            $connection->convertToPHPValue($data['created_at'], Types::DATETIME_IMMUTABLE),
            $connection->convertToPHPValue($data['metadata'], Types::JSON) + [self::METADATA_POSITION => $data['id']],
            $connection->convertToPHPValue($data['parameters'], Types::JSON),
        );
    }
}
