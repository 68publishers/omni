<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Bridge\Nette\DI;

use Doctrine\DBAL\Types\Type;
use Nette\Utils\Finder;
use ReflectionClass;
use ReflectionException;
use SixtyEightPublishers\DoctrineBridge\Bridge\Nette\DI\DatabaseType;
use function array_reverse;
use function array_slice;
use function assert;
use function class_exists;
use function dirname;
use function explode;
use function file_exists;
use function implode;
use function is_subclass_of;
use function mb_substr_count;
use function realpath;
use function reset;

trait AutoRegisterDoctrineTypesTrait
{
    protected ?string $doctrineTypesDirectory = null;

    /**
     * @return array<DatabaseType>
     * @throws ReflectionException
     */
    public function getDatabaseTypes(): array
    {
        $directory = $this->doctrineTypesDirectory;
        $reflection = new ReflectionClass($this);

        if (null === $directory) {
            $directory = dirname((string) $reflection->getFileName()) . '/../../../Infrastructure/Doctrine/DbalType';
        }

        $realpath = realpath($directory);

        if (false === $realpath || !file_exists($realpath)) {
            return [];
        }

        $baseNamespaceParts = array_reverse(explode('\\', $reflection->getNamespaceName()));
        $baseNamespaceParts = array_slice($baseNamespaceParts, mb_substr_count($directory, '..'));

        $bundleRootNamespacePart = reset($baseNamespaceParts);
        $namespace = implode('\\', array_reverse($baseNamespaceParts));
        $appendNamespacePart = false;

        foreach (explode('/', $realpath) as $pathPart) {
            if ($pathPart === $bundleRootNamespacePart) {
                $appendNamespacePart = true;

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

            if (!$classReflection->isInstantiable() || !is_subclass_of($classname, Type::class, true)) {
                continue;
            }

            $type = $classReflection->newInstanceWithoutConstructor();

            assert($type instanceof Type);

            $databaseTypes[] = new DatabaseType($type->getName(), $classname);
        }

        return $databaseTypes;
    }
}
