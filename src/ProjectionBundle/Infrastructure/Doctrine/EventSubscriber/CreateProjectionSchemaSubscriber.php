<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ProjectionBundle\Infrastructure\Doctrine\EventSubscriber;

use Doctrine\Common\EventSubscriber;
use Doctrine\DBAL\Schema\SchemaException;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Tools\Event\GenerateSchemaEventArgs;
use Doctrine\ORM\Tools\ToolEvents;

final class CreateProjectionSchemaSubscriber implements EventSubscriber
{
    public function __construct(
        private readonly string $tableName,
    ) {}

    public function getSubscribedEvents(): array
    {
        return [
            ToolEvents::postGenerateSchema,
        ];
    }

    /**
     * @throws SchemaException
     */
    public function postGenerateSchema(GenerateSchemaEventArgs $args): void
    {
        $schema = $args->getSchema();
        $table = $schema->createTable($this->tableName);

        $table->addColumn('id', Types::BIGINT)
            ->setNotnull(true)
            ->setAutoincrement(true);

        $table->addColumn('position', Types::BIGINT)
            ->setNotnull(true);

        $table->addColumn('projection_name', Types::STRING)
            ->setNotnull(true);

        $table->addColumn('aggregate_name', Types::STRING)
            ->setNotnull(true);

        $table->addColumn('created_at', Types::DATETIME_IMMUTABLE)
            ->setNotnull(true);

        $table->addColumn('last_update_at', Types::DATETIME_IMMUTABLE)
            ->setNotnull(true);

        $table->setPrimaryKey(['id']);
        $table->addUniqueIndex(['projection_name', 'aggregate_name'], 'uniq_' . $this->tableName . '_pn_an');
    }
}
