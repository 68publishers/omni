<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Bridge\Nette\DI;

use Nette\DI\Compiler;
use Nette\DI\CompilerExtension;

final class ArchitectureBundleExtension extends CompilerExtension
{
	use CompilerExtensionUtilsTrait;

	public const EXTENSION_POSTFIX_MESSENGER = 'messenger';

	/**
	 * {@inheritDoc}
	 */
	public function setCompiler(Compiler $compiler, string $name): self
	{
		parent::setCompiler($compiler, $name);

		$compiler->addExtension(sprintf('%s.%s', $name, self::EXTENSION_POSTFIX_MESSENGER), new ConfiguredMessengerExtension());

		return $this;
	}

	/**
	 * {@inheritDoc}
	 */
	public function loadConfiguration(): void
	{
		$this->requireCompilerExtension(InfrastructureExtensionInterface::class);
		$this->setBundleParameter('extension_name', $this->name);
		$this->loadConfigurationDir(__DIR__ . '/config/architecture_bundle');
	}
}
