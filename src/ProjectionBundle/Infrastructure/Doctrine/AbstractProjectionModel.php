<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ProjectionBundle\Infrastructure\Doctrine;

use DateTime;
use DateInterval;
use JsonSerializable;
use Ramsey\Uuid\Uuid;
use DateTimeImmutable;
use BadMethodCallException;
use Doctrine\DBAL\Types\Types;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\ORM\EntityManagerInterface;
use SixtyEightPublishers\ProjectionBundle\ProjectionModel\ProjectionModelInterface;

abstract class AbstractProjectionModel implements ProjectionModelInterface
{
	private string $tableName;

	private EntityManagerInterface $em;

	protected array $customTypes = [];

	public function __construct(string $tableName, EntityManagerInterface $em)
	{
		$this->tableName = $tableName;
		$this->em = $em;
	}

	/**
	 * @throws \Doctrine\DBAL\Schema\SchemaException
	 */
	abstract public function createSchema(Schema $schema): void;

	/**
	 * @throws \Doctrine\DBAL\Exception
	 */
	public function reset(): void
	{
		$connection = $this->em->getConnection();

		$connection->executeQuery('TRUNCATE TABLE ' . $this->getTableName() . ' RESTART IDENTITY');
	}

	/**
	 * @throws \Doctrine\DBAL\Exception
	 */
	public function insert(array $values): void
	{
		$connection = $this->em->getConnection();

		$types = $this->quoteKeys($this->resolveTypes($values));
		$values = $this->quoteKeys($values);

		$connection->insert($this->getTableName(), $values, $types);
	}

	/**
	 * @throws \Doctrine\DBAL\Exception
	 */
	public function update(array $values, array $criteria): void
	{
		$connection = $this->em->getConnection();

		$types = $this->quoteKeys($this->resolveTypes(array_merge($values, $criteria)));

		$values = $this->quoteKeys($values);
		$criteria = $this->quoteKeys($criteria);

		$connection->update($this->getTableName(), $values, $criteria, $types);
	}

	/**
	 * @throws \Doctrine\DBAL\Exception
	 */
	public function delete(array $criteria): void
	{
		$connection = $this->em->getConnection();

		$types = $this->quoteKeys($this->resolveTypes($criteria));
		$criteria = $this->quoteKeys($criteria);

		$connection->delete($this->getTableName(), $criteria, $types);
	}

	/**
	 * @throws \BadMethodCallException
	 */
	public function execute(string $action, ...$args): void
	{
		if (!method_exists($this, $action)) {
			throw new BadMethodCallException(sprintf(
				'Can not execute action "%s" on projection model %s. Method %s::%s() does not exists.',
				$action,
				static::class,
				static::class,
				$action
			));
		}

		$this->{$action}(...$args);
	}

	protected function quoteIdentifier(string $identifier): string
	{
		return $this->em->getConnection()->quoteIdentifier($identifier);
	}

	protected function quoteKeys(array $array): array
	{
		return array_combine(
			array_map(
				fn ($key) => $this->quoteIdentifier((string) $key),
				array_keys($array)
			),
			$array
		);
	}

	protected function resolveTypes(array $values): array
	{
		$types = [];

		foreach ($values as $column => $value) {
			switch (TRUE) {
				case NULL === $value:
					continue 2;
				case isset($this->customTypes[$column]):
					$type = $this->customTypes[$column];

					break;
				case $value instanceof DateTime:
					$type = Types::DATETIME_MUTABLE;

					break;
				case $value instanceof DateTimeImmutable:
					$type = Types::DATETIME_IMMUTABLE;

					break;
				case $value instanceof DateInterval:
					$type = Types::DATEINTERVAL;

					break;
				case $value instanceof JsonSerializable || is_array($value):
					$type = Types::JSON;

					break;
				case is_int($value):
					$type = Types::INTEGER;

					break;
				case is_float($value):
					$type = Types::FLOAT;

					break;
				case is_bool($value):
					$type = Types::BOOLEAN;

					break;
				case Uuid::isValid($value):
					$type = Types::GUID;

					break;
				default:
					$type = Types::STRING;
			}

			$types[$column] = $type;
		}

		return $types;
	}

	protected function getTableName(): string
	{
		return $this->quoteIdentifier($this->tableName);
	}
}
