<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ProjectionBundle\Infrastructure\Doctrine;

use DateTimeZone;
use DateTimeImmutable;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\DBAL\Exception as DbalException;
use Doctrine\DBAL\Exception\RetryableException;
use SixtyEightPublishers\ProjectionBundle\Projection\ProjectionInterface;
use SixtyEightPublishers\ProjectionBundle\ProjectionStore\ProjectionStoreException;
use SixtyEightPublishers\ProjectionBundle\ProjectionStore\ProjectionStoreInterface;

final class DoctrineProjectionStore implements ProjectionStoreInterface
{
	private string $tableName;

	private EntityManagerInterface $em;

	public function __construct(string $tableName, EntityManagerInterface $em)
	{
		$this->tableName = $tableName;
		$this->em = $em;
	}

	public function findLastPositions(string $projectionClassname): array
	{
		assert(is_subclass_of($projectionClassname, ProjectionInterface::class, TRUE));

		$aggregateClassnames = [];

		foreach ($projectionClassname::defineEvents() as $event) {
			$aggregateClassnames[] = $event->aggregateRootClassname;
		}

		$aggregateClassnames = array_unique($aggregateClassnames);

		if (empty($aggregateClassnames)) {
			return [];
		}

		$result = array_fill_keys($aggregateClassnames, '0');

		try {
			$connection = $this->em->getConnection();

			$qb = $connection->createQueryBuilder()
				->select('aggregate_name, position')
				->from($this->getTableName())
				->where('projection_name = :projectionName')
				->andWhere('aggregate_name IN (:aggregateNames)')
				->setParameter('projectionName', $projectionClassname::projectionName(), 'string')
				->setParameter('aggregateNames', $aggregateClassnames, Connection::PARAM_STR_ARRAY);

			$statement = $qb->executeQuery();

			while (FALSE !== ($data = $statement->fetchAssociative())) {
				$result[$data['aggregate_name']] = (string) $data['position'];
			}
		} catch (DbalException $e) {
			throw ProjectionStoreException::unableToFindLastPositions($projectionClassname, $e instanceof RetryableException, $e);
		}

		return $result;
	}

	/**
	 * @throws \Exception
	 */
	public function updateLastPosition(string $projectionClassname, string $aggregateClassname, string $position): bool
	{
		assert(is_subclass_of($projectionClassname, ProjectionInterface::class, TRUE));

		$connection = $this->em->getConnection();
		$now = new DateTimeImmutable('now', new DateTimeZone('UTC'));

		try {
			$result = 0 < (int) $connection->update(
				$this->getTableName(),
				[
					'position' => $position,
					'last_update_at' => $now,
				],
				[
					'projection_name' => $projectionClassname::projectionName(),
					'aggregate_name' => $aggregateClassname,
				],
				[
					'position' => Types::BIGINT,
					'projection_name' => Types::STRING,
					'aggregate_name' => Types::STRING,
					'last_update_at' => Types::DATETIME_IMMUTABLE,
				]
			);

			if (!$result) {
				$result = 0 < (int) $connection->insert(
					$this->getTableName(),
					[
						'position' => $position,
						'projection_name' => $projectionClassname::projectionName(),
						'aggregate_name' => $aggregateClassname,
						'created_at' => $now,
						'last_update_at' => $now,
					],
					[
						'position' => Types::BIGINT,
						'projection_name' => Types::STRING,
						'aggregate_name' => Types::STRING,
						'created_at' => Types::DATETIME_IMMUTABLE,
						'last_update_at' => Types::DATETIME_IMMUTABLE,
					]
				);
			}
		} catch (DbalException $e) {
			throw ProjectionStoreException::unableToUpdateLastPosition($projectionClassname, $aggregateClassname, $e instanceof RetryableException, $e);
		}

		return $result;
	}

	public function resetProjection(string $projectionClassname): void
	{
		assert(is_subclass_of($projectionClassname, ProjectionInterface::class, TRUE));

		$connection = $this->em->getConnection();

		try {
			$connection->delete(
				$this->getTableName(),
				[
					'projection_name' => $projectionClassname::projectionName(),
				],
				[
					'projection_name' => Types::STRING,
				]
			);
		} catch (DbalException $e) {
			throw ProjectionStoreException::unableToResetProjection($projectionClassname, $e instanceof RetryableException, $e);
		}
	}

	private function getTableName(): string
	{
		return $this->em->getConnection()->quoteIdentifier($this->tableName);
	}
}
