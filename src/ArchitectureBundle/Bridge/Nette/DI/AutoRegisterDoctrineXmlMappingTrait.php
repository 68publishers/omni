<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Bridge\Nette\DI;

use Nette\Utils\Finder;
use ReflectionClass;
use SixtyEightPublishers\DoctrineBridge\Bridge\Nette\DI\EntityMapping;
use SixtyEightPublishers\DoctrineBridge\Bridge\Nette\DI\TargetEntityProviderInterface;
use function array_merge;
use function dirname;
use function file_exists;
use function realpath;
use function str_replace;

trait AutoRegisterDoctrineXmlMappingTrait
{
    /**
     * @return array<EntityMapping>
     */
    public function getEntityMappings(): array
    {
        $directories = $this->getMappingDirectories();

        if (null === $directories) {
            $reflection = new ReflectionClass($this);
            $directories = [dirname((string) $reflection->getFileName()) . '/../../../Infrastructure/Doctrine/Mapping'];
        }

        $mappings = [];

        foreach ($directories as $directory) {
            $mappings[] = $this->getEntityMappingsForDirectory($directory);
        }

        return array_merge(...$mappings);
    }

    /**
     * @return array<string>|null
     */
    protected function getMappingDirectories(): ?array
    {
        return null;
    }

    /**
     * @return array<EntityMapping>
     */
    private function getEntityMappingsForDirectory(string $directory): array
    {
        $realpath = realpath($directory);

        if (false === $realpath || !file_exists($realpath)) {
            return [];
        }

        # Mapping files for ValueObjects (embeddables)
        $entityMappings = [];
        $resolvedTargetEntities = $this instanceof TargetEntityProviderInterface ? $this->getTargetEntities() : [];

        # Mapping files for Aggregates
        foreach (Finder::findFiles('*.dcm.xml')->in($realpath) as $file) {
            $aggregateClassname = str_replace('.', '\\', $file->getBasename('.dcm.xml'));

            foreach ($resolvedTargetEntities as $targetEntity) {
                # An aggregate is extended in the project, don't load mapping for the aggregate
                if ($targetEntity->originalEntity === $aggregateClassname && $targetEntity->originalEntity !== $targetEntity->newEntity) {
                    continue 2;
                }
            }

            $entityMappings[] = new EntityMapping(EntityMapping::DRIVER_XML, $aggregateClassname, $realpath);
        }

        return $entityMappings;
    }
}
