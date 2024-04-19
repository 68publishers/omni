<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Infrastructure\Doctrine\EventSubscriber;

use Doctrine\Common\EventSubscriber;
use Doctrine\DBAL\Schema\SchemaException;
use Doctrine\ORM\Tools\Event\GenerateSchemaTableEventArgs;
use Doctrine\ORM\Tools\ToolEvents;
use LogicException;
use SixtyEightPublishers\ArchitectureBundle\Domain\AggregateRootInterface;
use SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\EventId;
use SixtyEightPublishers\ArchitectureBundle\EventStore\EventStoreNameResolver;
use SixtyEightPublishers\ArchitectureBundle\Infrastructure\Doctrine\EventStore\DoctrineEventStore;
use function array_combine;
use function count;
use function is_subclass_of;
use function sprintf;

final class CreateEventStreamSubscriber implements EventSubscriber
{
    public function __construct(
        private readonly EventStoreNameResolver $eventStoreNameResolver,
    ) {}

    public function getSubscribedEvents(): array
    {
        return [
            ToolEvents::postGenerateSchemaTable,
        ];
    }

    /**
     * @throws SchemaException
     */
    public function postGenerateSchemaTable(GenerateSchemaTableEventArgs $args): void
    {
        if (!is_subclass_of($args->getClassMetadata()->getName(), AggregateRootInterface::class, true)) {
            return;
        }

        if (DoctrineEventStore::NAME !== $this->eventStoreNameResolver->resolve($args->getClassMetadata()->getName())) {
            return;
        }

        $schema = $args->getSchema();
        $classMetadata = $args->getClassMetadata();
        $tableName = $args->getClassTable()->getName() . '_event_stream';
        $table = $schema->createTable($tableName);

        $identifierColumns = array_combine(
            keys: $classMetadata->getIdentifierFieldNames(),
            values: $classMetadata->getIdentifierColumnNames(),
        );

        if (0 >= count($identifierColumns)) {
            throw new LogicException(
                message: sprintf(
                    'Entity of type %s has no identifier defined.',
                    $classMetadata->getName(),
                ),
            );
        }

        $table->addColumn('id', 'bigint')
            ->setNotnull(true)
            ->setAutoincrement(true);

        $table->addColumn('event_id', EventId::class)
            ->setNotnull(true);

        $table->addColumn('event_name', 'string')
            ->setNotnull(true);

        $table->addColumn('created_at', 'datetime_immutable')
            ->setNotnull(true);

        $table->addColumn('parameters', 'json')
            ->setNotnull(true)
            ->setPlatformOption('jsonb', true);

        $table->addColumn('metadata', 'json')
            ->setNotnull(true)
            ->setPlatformOption('jsonb', true);

        $aggregateIdColumns = [];

        foreach ($identifierColumns as $fieldName => $columnName) {
            $table->addColumn('aggregate_' . $columnName, $classMetadata->getTypeOfField($fieldName) ?? 'guid')
                ->setNotnull($classMetadata->isNullable($fieldName));

            $aggregateIdColumns[] = 'aggregate_' . $columnName;
        }

        $table->setPrimaryKey(['id']);
        $table->addIndex(['event_id'], 'idx_' . $tableName . '_event_id');
        $table->addIndex($aggregateIdColumns, 'idx_' . $tableName . '_aggregate_id');
        $table->addIndex(['created_at'], 'idx_' . $tableName . '_created_at');
        $table->addUniqueIndex(['event_id'], 'uniq_' . $tableName . '_event_id');
    }
}
