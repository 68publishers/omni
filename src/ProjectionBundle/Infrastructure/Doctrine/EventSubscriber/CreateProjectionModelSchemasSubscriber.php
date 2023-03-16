<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ProjectionBundle\Infrastructure\Doctrine\EventSubscriber;

use Doctrine\Common\EventSubscriber;
use Doctrine\DBAL\Schema\SchemaException;
use Doctrine\ORM\Tools\Event\GenerateSchemaEventArgs;
use Doctrine\ORM\Tools\ToolEvents;
use SixtyEightPublishers\ProjectionBundle\Infrastructure\Doctrine\AbstractProjectionModel;
use SixtyEightPublishers\ProjectionBundle\ProjectionModel\ProjectionModelLocatorInterface;

final class CreateProjectionModelSchemasSubscriber implements EventSubscriber
{
    public function __construct(
        private readonly ProjectionModelLocatorInterface $projectionModelLocator,
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

        foreach ($this->projectionModelLocator->all() as $projectionModel) {
            if ($projectionModel instanceof AbstractProjectionModel) {
                $projectionModel->createSchema($schema);
            }
        }
    }
}
