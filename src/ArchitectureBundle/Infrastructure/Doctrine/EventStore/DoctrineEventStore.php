<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Infrastructure\Doctrine\EventStore;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Types;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\DBAL\Exception as DbalException;
use Doctrine\DBAL\Exception\RetryableException;
use SixtyEightPublishers\ArchitectureBundle\EventStore\EventCriteria;
use SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\EventId;
use SixtyEightPublishers\ArchitectureBundle\EventStore\EventStoreException;
use SixtyEightPublishers\ArchitectureBundle\EventStore\EventStoreInterface;
use SixtyEightPublishers\ArchitectureBundle\Domain\Event\AbstractDomainEvent;

final class DoctrineEventStore implements EventStoreInterface
{
	private EntityManagerInterface $em;

	/**
	 * @param \Doctrine\ORM\EntityManagerInterface $em
	 */
	public function __construct(EntityManagerInterface $em)
	{
		$this->em = $em;
	}

	/**
	 * {@inheritDoc}
	 */
	public function store(string $aggregateRootClassname, array $events): void
	{
		$connection = $this->em->getConnection();
		$tableName = $this->getTableName($aggregateRootClassname);

		foreach ($events as $event) {
			try {
				$connection->insert($tableName, [
					'event_id' => $event->eventId()->toString(),
					'aggregate_id' => $event->aggregateId()->toString(),
					'event_name' => $event->eventName(),
					'created_at' => $event->createdAt(),
					'parameters' => $event->parameters(),
					'metadata' => $event->metadata(),
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

	/**
	 * {@inheritDoc}
	 */
	public function get(string $aggregateRootClassname, EventId $eventId): ?AbstractDomainEvent
	{
		$connection = $this->em->getConnection();
		$tableName = $this->getTableName($aggregateRootClassname);

		$query = sprintf(
			'SELECT * FROM %s WHERE event_id = ? LIMIT 1;',
			$tableName
		);

		try {
			$statement = $connection->executeQuery($query, [$eventId->toString()], [Types::GUID]);
			$data = $statement->fetchAssociative();

			if (!$data) {
				return NULL;
			}

			return $this->hydrateEvent($connection, $data);
		} catch (DbalException $e) {
			throw EventStoreException::unableToGetEvent($aggregateRootClassname, $eventId, $e instanceof RetryableException, $e);
		}
	}

	/**
	 * {@inheritDoc}
	 */
	public function find(EventCriteria $criteria): array
	{
		$connection = $this->em->getConnection();
		$tableName = $this->getTableName($criteria->aggregateRootClassname());

		$qb = $connection->createQueryBuilder()
			->select('*')
			->from($tableName);

		$qb = $this->buildConditions($qb, $criteria);

		if (NULL !== $criteria->limit()) {
			$qb->setMaxResults($criteria->limit());
		}

		if (NULL !== $criteria->offset()) {
			$qb->setFirstResult($criteria->offset());
		}

		switch ($criteria->sorting()) {
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

			while (FALSE !== ($data = $statement->fetchAssociative())) {
				$events[] = $this->hydrateEvent($connection, $data);
			}
		} catch (DbalException $e) {
			throw EventStoreException::unableToFindEvents($criteria->aggregateRootClassname(), $e instanceof RetryableException, $e);
		}

		return $events;
	}

	/**
	 * {@inheritDoc}
	 */
	public function count(EventCriteria $criteria): int
	{
		$connection = $this->em->getConnection();
		$tableName = $this->getTableName($criteria->aggregateRootClassname());

		$qb = $connection->createQueryBuilder()
			->select('COUNT(id)')
			->from($tableName)
			->setMaxResults(1);

		$qb = $this->buildConditions($qb, $criteria);

		try {
			return (int) $qb->executeQuery()->fetchOne();
		} catch (DbalException $e) {
			throw EventStoreException::unableToCountEvents($criteria->aggregateRootClassname(), $e instanceof RetryableException, $e);
		}
	}

	/**
	 * @param \Doctrine\DBAL\Query\QueryBuilder                                 $qb
	 * @param \SixtyEightPublishers\ArchitectureBundle\EventStore\EventCriteria $criteria
	 *
	 * @return \Doctrine\DBAL\Query\QueryBuilder
	 */
	private function buildConditions(QueryBuilder $qb, EventCriteria $criteria): QueryBuilder
	{
		if (NULL !== $criteria->aggregateId()) {
			$qb->andWhere('aggregate_id = :aggregateId')
				->setParameter('aggregateId', $criteria->aggregateId()->toString());
		}

		if (NULL !== $criteria->createdBefore()) {
			$qb->andWhere('created_at <= :beforeDateTime')
				->setParameter('beforeDateTime', $criteria->createdBefore(), Types::DATETIME_IMMUTABLE);
		}

		if (NULL !== $criteria->createdAfter()) {
			$qb->andWhere('created_at >= :afterDateTime')
				->setParameter('afterDateTime', $criteria->createdAfter(), Types::DATETIME_IMMUTABLE);
		}

		if (NULL !== $criteria->positionGreaterThan()) {
			$qb->andWhere('id > :positionGreaterThan')
				->setParameter('positionGreaterThan', $criteria->positionGreaterThan(), Types::BIGINT);
		}

		if (NULL !== $criteria->positionLessThan()) {
			$qb->andWhere('id < :positionLessThan')
				->setParameter('positionLessThan', $criteria->positionLessThan(), Types::BIGINT);
		}

		if (!empty($criteria->eventNames())) {
			$qb->andWhere('event_name IN (:eventNames)')
				->setParameter('eventNames', $criteria->eventNames(), Connection::PARAM_STR_ARRAY);
		}

		return $qb;
	}

	/**
	 * @param string $aggregateRootClassname
	 *
	 * @return string
	 * @throws \SixtyEightPublishers\ArchitectureBundle\EventStore\EventStoreException
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
	 * @param \Doctrine\DBAL\Connection $connection
	 * @param array                     $data
	 *
	 * @return \SixtyEightPublishers\ArchitectureBundle\Domain\Event\AbstractDomainEvent
	 * @throws \Doctrine\DBAL\Exception
	 */
	private function hydrateEvent(Connection $connection, array $data): AbstractDomainEvent
	{
		$eventClassname = $data['event_name'];

		assert(is_subclass_of($eventClassname, AbstractDomainEvent::class, TRUE));

		return $eventClassname::reconstitute(
			$eventClassname,
			$connection->convertToPHPValue($data['event_id'], EventId::class),
			$connection->convertToPHPValue($data['created_at'], 'datetime_immutable'),
			$connection->convertToPHPValue($data['metadata'], 'json') + [self::METADATA_POSITION => $data['id']],
			$connection->convertToPHPValue($data['parameters'], 'json'),
		);
	}
}
