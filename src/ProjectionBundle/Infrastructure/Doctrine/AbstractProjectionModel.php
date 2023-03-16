<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ProjectionBundle\Infrastructure\Doctrine;

use BadMethodCallException;
use DateInterval;
use DateTime;
use DateTimeImmutable;
use Doctrine\DBAL\Exception as DbalException;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\SchemaException;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\EntityManagerInterface;
use JsonSerializable;
use Ramsey\Uuid\Uuid;
use SixtyEightPublishers\ProjectionBundle\ProjectionModel\ProjectionModelInterface;
use function array_combine;
use function array_keys;
use function array_map;
use function array_merge;
use function is_array;
use function is_bool;
use function is_float;
use function is_int;
use function method_exists;
use function sprintf;

abstract class AbstractProjectionModel implements ProjectionModelInterface
{
    /** @var array<string, string> */
    protected array $customTypes = [];

    public function __construct(
        private readonly string $tableName,
        private readonly EntityManagerInterface $em,
    ) {}

    /**
     * @throws SchemaException
     */
    abstract public function createSchema(Schema $schema): void;

    /**
     * @throws DbalException
     */
    public function reset(): void
    {
        $connection = $this->em->getConnection();

        $connection->executeQuery('TRUNCATE TABLE ' . $this->getTableName() . ' RESTART IDENTITY');
    }

    /**
     * @throws DbalException
     */
    public function insert(array $values): void
    {
        $connection = $this->em->getConnection();

        $types = $this->quoteKeys($this->resolveTypes($values));
        $values = $this->quoteKeys($values);

        $connection->insert($this->getTableName(), $values, $types);
    }

    /**
     * @throws DbalException
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
     * @throws DbalException
     */
    public function delete(array $criteria): void
    {
        $connection = $this->em->getConnection();

        $types = $this->quoteKeys($this->resolveTypes($criteria));
        $criteria = $this->quoteKeys($criteria);

        $connection->delete($this->getTableName(), $criteria, $types);
    }

    /**
     * @throws BadMethodCallException
     */
    public function execute(string $action, ...$args): void
    {
        if (!method_exists($this, $action)) {
            throw new BadMethodCallException(sprintf(
                'Can not execute action "%s" on projection model %s. Method %s::%s() does not exists.',
                $action,
                static::class,
                static::class,
                $action,
            ));
        }

        $this->{$action}(...$args);
    }

    protected function quoteIdentifier(string $identifier): string
    {
        return $this->em->getConnection()->quoteIdentifier($identifier);
    }

    /**
     * @param array<string> $array
     *
     * @return array<string>
     */
    protected function quoteKeys(array $array): array
    {
        return array_combine(
            array_map(
                fn ($key) => $this->quoteIdentifier((string) $key),
                array_keys($array),
            ),
            $array,
        );
    }

    /**
     * @param array<string, mixed> $values
     *
     * @return array<string, string>
     */
    protected function resolveTypes(array $values): array
    {
        $types = [];

        foreach ($values as $column => $value) {
            switch (true) {
                case null === $value:
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
