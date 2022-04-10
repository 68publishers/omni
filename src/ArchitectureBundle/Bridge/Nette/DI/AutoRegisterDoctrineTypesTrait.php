<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Bridge\Nette\DI;

use ReflectionClass;
use Nette\Utils\Finder;
use Doctrine\DBAL\Types\Type;
use SixtyEightPublishers\DoctrineBridge\DI\DatabaseType;

trait AutoRegisterDoctrineTypesTrait
{
	protected ?string $doctrineTypesDirectory = NULL;

	/**
	 * @return \SixtyEightPublishers\DoctrineBridge\DI\DatabaseType[]
	 * @throws \ReflectionException
	 */
	public function getDatabaseTypes(): array
	{
		$directory = $this->doctrineTypesDirectory;
		$reflection = new ReflectionClass($this);

		if (NULL === $directory) {
			$directory = dirname($reflection->getFileName()) . '/../../../Infrastructure/Doctrine/DbalType';
		}

		$realpath = realpath($directory);

		if (FALSE === $realpath || !file_exists($realpath)) {
			return [];
		}

		$baseNamespaceParts = array_reverse(explode('\\', $reflection->getNamespaceName()));
		$baseNamespaceParts = array_slice($baseNamespaceParts, substr_count($directory, '..'));

		$bundleRootNamespacePart = reset($baseNamespaceParts);
		$namespace = implode('\\', array_reverse($baseNamespaceParts));
		$appendNamespacePart = FALSE;

		foreach (explode('/', $realpath) as $pathPart) {
			if ($pathPart === $bundleRootNamespacePart) {
				$appendNamespacePart = TRUE;

				continue;
			}

			if (!$appendNamespacePart) {
				continue;
			}

			$namespace .= '\\' . $pathPart;
		}

		$databaseTypes = [];

		foreach (Finder::findFiles('*.php')->from($realpath) as $file) {
			$classname = $namespace . '\\' . $file->getBasename('.php');

			if (!class_exists($classname)) {
				continue;
			}

			$classReflection = new ReflectionClass($classname);

			if (!$classReflection->isInstantiable() || !is_subclass_of($classname, Type::class, TRUE)) {
				continue;
			}

			$type = $classReflection->newInstanceWithoutConstructor();

			assert($type instanceof Type);

			$databaseTypes[] = new DatabaseType($type->getName(), $classname, NULL, TRUE);
		}

		return $databaseTypes;
	}
}
