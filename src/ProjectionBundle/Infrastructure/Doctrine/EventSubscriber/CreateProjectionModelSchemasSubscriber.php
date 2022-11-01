<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ProjectionBundle\Infrastructure\Doctrine\EventSubscriber;

use Doctrine\ORM\Tools\ToolEvents;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Tools\Event\GenerateSchemaEventArgs;
use SixtyEightPublishers\ProjectionBundle\Infrastructure\Doctrine\AbstractProjectionModel;
use SixtyEightPublishers\ProjectionBundle\ProjectionModel\ProjectionModelLocatorInterface;

final class CreateProjectionModelSchemasSubscriber implements EventSubscriber
{
	private ProjectionModelLocatorInterface $projectionModelLocator;

	public function __construct(ProjectionModelLocatorInterface $projectionModelLocator)
	{
		$this->projectionModelLocator = $projectionModelLocator;
	}

	public function getSubscribedEvents(): array
	{
		return [
			ToolEvents::postGenerateSchema,
		];
	}

	public function postGenerateSchema(GenerateSchemaEventArgs $args): void
	{
		$schema = $args->getSchema();

		foreach ($this->projectionModelLocator->all() as $projectionModel) {
			if ($projectionModel instanceof AbstractProjectionModel) {
				$projectionModel->createSchema($schema);
			}
		}
	}
}
