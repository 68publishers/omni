<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ProjectionBundle\Infrastructure\Doctrine\EventSubscriber;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Tools\ToolEvents;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Tools\Event\GenerateSchemaEventArgs;

final class CreateProjectionSchemaSubscriber implements EventSubscriber
{
	private string $tableName;

	public function __construct(string $tableName)
	{
		$this->tableName = $tableName;
	}

	public function getSubscribedEvents(): array
	{
		return [
			ToolEvents::postGenerateSchema,
		];
	}

	/**
	 * @throws \Doctrine\DBAL\Schema\SchemaException
	 */
	public function postGenerateSchema(GenerateSchemaEventArgs $args): void
	{
		$schema = $args->getSchema();
		$table = $schema->createTable($this->tableName);

		$table->addColumn('id', Types::BIGINT)
			->setNotnull(TRUE)
			->setAutoincrement(TRUE);

		$table->addColumn('position', Types::BIGINT)
			->setNotnull(TRUE);

		$table->addColumn('projection_name', Types::STRING)
			->setNotnull(TRUE);

		$table->addColumn('aggregate_name', Types::STRING)
			->setNotnull(TRUE);

		$table->addColumn('created_at', Types::DATETIME_IMMUTABLE)
			->setNotnull(TRUE);

		$table->addColumn('last_update_at', Types::DATETIME_IMMUTABLE)
			->setNotnull(TRUE);

		$table->setPrimaryKey(['id']);
		$table->addUniqueIndex(['projection_name', 'aggregate_name'], 'uniq_' . $this->tableName . '_projection_name_aggregate_name');
	}
}
