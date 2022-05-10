<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Bridge\Nette\DI;

use ReflectionClass;
use Nette\Utils\Finder;
use SixtyEightPublishers\DoctrineBridge\DI\EntityMapping;

trait AutoRegisterDoctrineXmlMappingTrait
{
	use ExtendedAggregatesResolverTrait;

	protected ?string $xmlMappingDirectory = NULL;

	/**
	 * @return \SixtyEightPublishers\DoctrineBridge\DI\EntityMapping[]
	 */
	public function getEntityMappings(): array
	{
		$directory = $this->xmlMappingDirectory;
		$reflection = new ReflectionClass($this);

		if (NULL === $directory) {
			$directory = dirname($reflection->getFileName()) . '/../../../Infrastructure/Doctrine/Mapping';
		}

		$realpath = realpath($directory);

		if (FALSE === $realpath || !file_exists($realpath)) {
			return [];
		}

		# Mapping files for ValueObjects (embeddables)
		$entityMappings = [];
		$resolvedTargetEntities = $this->resolveExtendedAggregates();

		# Mapping files for Aggregates
		foreach (Finder::findFiles('*.dcm.xml')->in($realpath) as $file) {
			$aggregateClassname = str_replace('.', '\\', $file->getBasename('.dcm.xml'));

			foreach ($resolvedTargetEntities as $originalClassname => $usedClassname) {
				# An aggregate is extended in the project, don't load mapping for the aggregate
				if ($originalClassname === $aggregateClassname && $originalClassname !== $usedClassname) {
					continue 2;
				}
			}

			$entityMappings[] = new EntityMapping(EntityMapping::DRIVER_XML, $aggregateClassname, $realpath);
		}

		return $entityMappings;
	}
}
