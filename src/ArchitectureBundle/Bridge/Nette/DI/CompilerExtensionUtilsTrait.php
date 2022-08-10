<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Bridge\Nette\DI;

use ReflectionClass;
use Nette\Utils\Finder;
use Nette\DI\ContainerBuilder;
use Nette\DI\CompilerExtension;
use Nette\InvalidStateException;

/**
 * @property-read \Nette\DI\Compiler $compiler
 *
 * @method void             loadDefinitionsFromConfig(array $config)
 * @method array            loadFromFile(string $path)
 * @method ContainerBuilder getContainerBuilder()
 */
trait CompilerExtensionUtilsTrait
{
	/**
	 * @param string $extensionClassname
	 * @param bool   $throw
	 *
	 * @return \Nette\DI\CompilerExtension|NULL
	 * @throws \Nette\InvalidStateException
	 */
	protected function requireCompilerExtension(string $extensionClassname, bool $throw = TRUE): ?CompilerExtension
	{
		$extensions = $this->compiler->getExtensions($extensionClassname);

		if ($throw && 0 >= count($extensions)) {
			throw new InvalidStateException(sprintf(
				'The extension %s can be used only with %s.',
				static::class,
				$extensionClassname
			));
		}

		$extension = reset($extensions);

		return FALSE !== $extension ? $extension : NULL;
	}

	/**
	 * @param string $commonClassOrInterface
	 *
	 * @return void
	 * @throws \Nette\InvalidStateException
	 */
	protected function checkCompilerExtensionConcurrency(string $commonClassOrInterface): void
	{
		if (!$this instanceof $commonClassOrInterface) {
			throw new InvalidStateException(sprintf(
				'The extension %s must be instance of %s.',
				static::class,
				$commonClassOrInterface
			));
		}

		foreach ($this->compiler->getExtensions($commonClassOrInterface) as $extension) {
			if ($this !== $extension) {
				throw new InvalidStateException(sprintf(
					'Concurrent compiler extensions %s and %s detected. The only one extension of type %s can be loaded.',
					static::class,
					get_class($extension),
					$commonClassOrInterface
				));
			}
		}
	}

	/**
	 * @param string|string[] $paths
	 * @param bool            $recursively
	 *
	 * @return void
	 */
	protected function loadConfigurationDir($paths, bool $recursively = TRUE): void
	{
		$paths = (array) $paths;
		$finder = Finder::findFiles('*.neon');
		$finder = $recursively ? $finder->from(...$paths) : $finder->in(...$paths);

		foreach (array_keys(iterator_to_array($finder)) as $filename) {
			$this->loadDefinitionsFromConfig(
				$this->loadFromFile($filename)['services']
			);
		}
	}

	/**
	 * @param string $name
	 * @param mixed  $value
	 *
	 * @return void
	 */
	protected function setBundleParameter(string $name, $value): void
	{
		$builder = $this->getContainerBuilder();
		$reflection = new ReflectionClass($this);

		$bundleName = preg_replace('#Extension$#', '', $reflection->getShortName());
		$bundleName = mb_strtolower(preg_replace('/[A-Z]/', '_\\0', lcfirst($bundleName)));

		$builder->parameters['68publishers'][$bundleName][$name] = $value;
	}
}
