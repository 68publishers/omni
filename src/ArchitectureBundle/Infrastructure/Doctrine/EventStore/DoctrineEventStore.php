<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Infrastructure\Doctrine\EventStore;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\EntityManagerInterface;
use SixtyEightPublishers\ArchitectureBundle\EventStore\EventCriteria;
use SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\EventId;
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
	 *
	 * @throws \Doctrine\DBAL\Exception
	 */
	public function store(string $aggregateRootClassname, array $events): void
	{
		$connection = $this->em->getConnection();
		$tableName = $this->getTableName($aggregateRootClassname);

		foreach ($events as $event) {
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
		}
	}

	/**
	 * {@inheritDoc}
	 *
	 * @throws \Doctrine\DBAL\Exception
	 * @throws \Exception
	 */
	public function get(string $aggregateRootClassname, EventId $eventId): ?AbstractDomainEvent
	{
		$connection = $this->em->getConnection();
		$tableName = $this->getTableName($aggregateRootClassname);

		$query = sprintf(
			'SELECT * FROM %s WHERE event_id = ? LIMIT 1;',
			$tableName
		);

		$statement = $connection->executeQuery($query, [$eventId->toString()], [Types::GUID]);
		$data = $statement->fetchAssociative();

		if (!$data) {
			return NULL;
		}

		return $this->hydrateEvent($connection, $data);
	}

	/**
	 * {@inheritDoc}
	 *
	 * @throws \Doctrine\DBAL\Exception
	 */
	public function find(EventCriteria $criteria): array
	{
		$connection = $this->em->getConnection();
		$tableName = $this->getTableName($criteria->aggregateRootClassname());

		$qb = $connection->createQueryBuilder()
			->select('*')
			->from($tableName);

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
		}

		$statement = $qb->executeQuery();
		$events = [];

		while (FALSE !== ($data = $statement->fetchAssociative())) {
			$events[] = $this->hydrateEvent($connection, $data);
		}

		return $events;
	}

	/**
	 * @param string $aggregateRootClassname
	 *
	 * @return string
	 * @throws \Doctrine\DBAL\Exception
	 */
	private function getTableName(string $aggregateRootClassname): string
	{
		$classMetadata = $this->em->getClassMetadata($aggregateRootClassname);
		$tableName = $classMetadata->getTableName();
		$quoteCharacter = $this->em->getConnection()->getDatabasePlatform()->getIdentifierQuoteCharacter();

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
			$connection->convertToPHPValue($data['metadata'], 'json'),
			$connection->convertToPHPValue($data['parameters'], 'json'),
		);
	}
}
