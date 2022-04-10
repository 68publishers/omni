<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Bridge\Nette\DI;

use Nette\DI\CompilerExtension;
use SixtyEightPublishers\DoctrineBridge\DI\DoctrineBridgeExtension;
use SixtyEightPublishers\DoctrineBridge\DI\DatabaseTypeProviderInterface;

final class DoctrineInfrastructureExtension extends CompilerExtension implements InfrastructureExtensionInterface, DatabaseTypeProviderInterface
{
	use CompilerExtensionUtilsTrait;
	use AutoRegisterDoctrineTypesTrait;

	/**
	 * {@inheritDoc}
	 */
	public function loadConfiguration(): void
	{
		$this->requireCompilerExtension(ArchitectureBundleExtension::class);
		$this->requireCompilerExtension(DoctrineBridgeExtension::class);
		$this->checkCompilerExtensionConcurrency(InfrastructureExtensionInterface::class);
		$this->loadConfigurationDir(__DIR__ . '/config/doctrine_infrastructure');
	}
}
