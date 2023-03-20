<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Bridge\Nette\DI;

use Doctrine\DBAL\Types\Type;
use Nette\PhpGenerator\PhpFile;
use Nette\Utils\Finder;
use ReflectionClass;
use ReflectionException;
use RuntimeException;
use SixtyEightPublishers\DoctrineBridge\Bridge\Nette\DI\DatabaseType;
use function array_merge;
use function assert;
use function class_exists;
use function dirname;
use function file_exists;
use function file_get_contents;
use function is_subclass_of;
use function realpath;
use function sprintf;

trait AutoRegisterDoctrineTypesTrait
{
    /**
     * @return array<DatabaseType>
     * @throws ReflectionException
     */
    public function getDatabaseTypes(): array
    {
        $directories = $this->getDatabaseTypesDirectories();

        if (null === $directories) {
            $reflection = new ReflectionClass($this);
            $directories = [dirname((string) $reflection->getFileName()) . '/../../../Infrastructure/Doctrine/DbalType'];
        }

        $mappings = [];

        foreach ($directories as $directory) {
            $mappings[] = $this->getDatabaseTypesForDirectory($directory);
        }

        return array_merge(...$mappings);
    }

    /**
     * @return array<string>|null
     */
    protected function getDatabaseTypesDirectories(): ?array
    {
        return null;
    }

    /**
     * @return array<DatabaseType>
     * @throws ReflectionException
     */
    private function getDatabaseTypesForDirectory(string $directory): array
    {
        $realpath = realpath($directory);

        if (false === $realpath || !file_exists($realpath)) {
            return [];
        }

        $databaseTypes = [];

        foreach (Finder::findFiles('*.php')->from($realpath) as $file) {
            $fileRealPath = $file->getRealPath();

            if (false === $fileRealPath) {
                throw new RuntimeException(sprintf(
                    'Unable to get real path of the file "%s".',
                    $file->getPathname(),
                ));
            }

            $fileContents = file_get_contents($fileRealPath);

            if (false === $fileContents) {
                throw new RuntimeException(sprintf(
                    'Unable to read the file "%s".',
                    $fileRealPath,
                ));
            }

            $phpFile = PhpFile::fromCode($fileContents);

            foreach ($phpFile->getNamespaces() as $namespace) {
                foreach ($namespace->getClasses() as $class) {
                    $classname = $namespace->getName() . '\\' . $class->getName();

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
            }
        }

        return $databaseTypes;
    }
}
