<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Infrastructure\Doctrine\EventSubscriber;

use Doctrine\Common\EventSubscriber;
use Doctrine\DBAL\Schema\SchemaException;
use Doctrine\ORM\Tools\Event\GenerateSchemaTableEventArgs;
use Doctrine\ORM\Tools\ToolEvents;
use SixtyEightPublishers\ArchitectureBundle\Domain\AggregateRootInterface;
use SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\AggregateId;
use SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\EventId;
use function is_subclass_of;

final class CreateEventStreamSubscriber implements EventSubscriber
{
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

        $schema = $args->getSchema();
        $tableName = $args->getClassTable()->getName() . '_event_stream';
        $table = $schema->createTable($tableName);

        $table->addColumn('id', 'bigint')
            ->setNotnull(true)
            ->setAutoincrement(true);

        $table->addColumn('event_id', EventId::class)
            ->setNotnull(true);

        $table->addColumn('aggregate_id', AggregateId::class)
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

        $table->setPrimaryKey(['id']);
        $table->addIndex(['event_id'], 'idx_' . $tableName . '_event_id');
        $table->addIndex(['aggregate_id'], 'idx_' . $tableName . '_aggregate_id');
        $table->addIndex(['created_at'], 'idx_' . $tableName . '_created_at');
        $table->addUniqueIndex(['event_id'], 'uniq_' . $tableName . '_event_id');
    }
}
