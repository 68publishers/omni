<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Infrastructure\Doctrine\EventSubscriber;

use Doctrine\ORM\Tools\ToolEvents;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Tools\Event\GenerateSchemaTableEventArgs;
use SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\EventId;
use SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\AggregateId;
use SixtyEightPublishers\ArchitectureBundle\Domain\Aggregate\AggregateRootInterface;

final class CreateEventStreamSubscriber implements EventSubscriber
{
	/**
	 * {@inheritDoc}
	 */
	public function getSubscribedEvents(): array
	{
		return [
			ToolEvents::postGenerateSchemaTable,
		];
	}

	/**
	 * @param \Doctrine\ORM\Tools\Event\GenerateSchemaTableEventArgs $args
	 *
	 * @return void
	 * @throws \Doctrine\DBAL\Schema\SchemaException
	 */
	public function postGenerateSchemaTable(GenerateSchemaTableEventArgs $args): void
	{
		if (!is_subclass_of($args->getClassMetadata()->getName(), AggregateRootInterface::class, TRUE)) {
			return;
		}

		$schema = $args->getSchema();
		$tableName = $args->getClassTable()->getName() . '_event_stream';
		$table = $schema->createTable($tableName);

		$table->addColumn('id', 'bigint')
			->setNotnull(TRUE)
			->setAutoincrement(TRUE);

		$table->addColumn('event_id', EventId::class)
			->setNotnull(TRUE);

		$table->addColumn('aggregate_id', AggregateId::class)
			->setNotnull(TRUE);

		$table->addColumn('event_name', 'string')
			->setNotnull(TRUE);

		$table->addColumn('created_at', 'datetime_immutable')
			->setNotnull(TRUE);

		$table->addColumn('parameters', 'json')
			->setNotnull(TRUE)
			->setPlatformOption('jsonb', TRUE);

		$table->addColumn('metadata', 'json')
			->setNotnull(TRUE)
			->setPlatformOption('jsonb', TRUE);

		$table->setPrimaryKey(['id']);
		$table->addIndex(['event_id'], 'idx_' . $tableName . '_event_id');
		$table->addIndex(['aggregate_id'], 'idx_' . $tableName . '_aggregate_id');
		$table->addIndex(['created_at'], 'idx_' . $tableName . '_created_at');
		$table->addUniqueIndex(['event_id'], 'uniq_' . $tableName . '_event_id');
	}
}
