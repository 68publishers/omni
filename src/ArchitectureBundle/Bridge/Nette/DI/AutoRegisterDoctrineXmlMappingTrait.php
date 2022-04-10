<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Bridge\Nette\DI;

use ReflectionClass;
use SixtyEightPublishers\DoctrineBridge\DI\EntityMapping;

trait AutoRegisterDoctrineXmlMappingTrait
{
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

		$baseNamespaceParts = array_reverse(explode('\\', $reflection->getNamespaceName()));
		$baseNamespaceParts = array_slice($baseNamespaceParts, substr_count($directory, '..'));
		$namespace = implode('\\', array_reverse($baseNamespaceParts)) . '\\Domain';

		return [
			new EntityMapping(EntityMapping::DRIVER_XML, $namespace, $realpath),
		];
	}
}
