<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Bridge\Nette\DI;

use Nette\DI\Compiler;
use Nette\DI\CompilerExtension;
use Nette\DI\ContainerBuilder;
use Nette\InvalidStateException;
use Nette\Utils\Finder;
use ReflectionClass;
use function array_keys;
use function count;
use function get_class;
use function iterator_to_array;
use function lcfirst;
use function mb_strtolower;
use function preg_replace;
use function reset;
use function sprintf;

/**
 * @property-read Compiler $compiler
 *
 * @method void             loadDefinitionsFromConfig(array $config)
 * @method array            loadFromFile(string $path)
 * @method ContainerBuilder getContainerBuilder()
 */
trait CompilerExtensionUtilsTrait
{
    /**
     * @param  class-string          $extensionClassname
     * @throws InvalidStateException
     */
    protected function requireCompilerExtension(string $extensionClassname, bool $throw = true): ?CompilerExtension
    {
        $extensions = $this->compiler->getExtensions($extensionClassname);

        if ($throw && 0 >= count($extensions)) {
            throw new InvalidStateException(sprintf(
                'The extension %s can be used only with %s.',
                static::class,
                $extensionClassname,
            ));
        }

        $extension = reset($extensions);

        return false !== $extension ? $extension : null;
    }

    /**
     * @throws InvalidStateException
     */
    protected function checkCompilerExtensionConcurrency(string $commonClassOrInterface): void
    {
        if (!$this instanceof $commonClassOrInterface) {
            throw new InvalidStateException(sprintf(
                'The extension %s must be instance of %s.',
                static::class,
                $commonClassOrInterface,
            ));
        }

        foreach ($this->compiler->getExtensions($commonClassOrInterface) as $extension) {
            if ($this !== $extension) {
                throw new InvalidStateException(sprintf(
                    'Concurrent compiler extensions %s and %s detected. The only one extension of type %s can be loaded.',
                    static::class,
                    get_class($extension),
                    $commonClassOrInterface,
                ));
            }
        }
    }

    /**
     * @param string|array<string> $paths
     */
    protected function loadConfigurationDir(string|array $paths, bool $recursively = true): void
    {
        $paths = (array) $paths;
        $finder = Finder::findFiles('*.neon');
        $finder = $recursively ? $finder->from(...$paths) : $finder->in(...$paths);

        foreach (array_keys(iterator_to_array($finder)) as $filename) {
            $this->loadDefinitionsFromConfig(
                $this->loadFromFile($filename)['services'],
            );
        }
    }

    protected function setBundleParameter(string $name, mixed $value, ?string $bundleName = null): void
    {
        $builder = $this->getContainerBuilder();
        $reflection = new ReflectionClass($this);

        if (null === $bundleName) {
            $bundleName = (string) preg_replace('#Extension$#', '', $reflection->getShortName());
            $bundleName = mb_strtolower((string) preg_replace('/[A-Z]/', '_\\0', lcfirst($bundleName)));
        }

        $builder->parameters['68publishers'][$bundleName][$name] = $value;
    }
}
