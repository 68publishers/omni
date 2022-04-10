<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Bridge\Nette\DI;

use Nette\DI\CompilerExtension;

final class InMemoryInfrastructureExtension extends CompilerExtension implements InfrastructureExtensionInterface
{
	use CompilerExtensionUtilsTrait;

	/**
	 * {@inheritDoc}
	 */
	public function loadConfiguration(): void
	{
		$this->requireCompilerExtension(ArchitectureBundleExtension::class);
		$this->checkCompilerExtensionConcurrency(InfrastructureExtensionInterface::class);
		$this->loadConfigurationDir(__DIR__ . '/config/in_memory_infrastructure');
	}
}
