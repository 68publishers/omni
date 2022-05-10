<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\Bridge\Nette\DI;

use Nette\DI\CompilerExtension;
use SixtyEightPublishers\DoctrineBridge\DI\DatabaseTypeProviderInterface;
use SixtyEightPublishers\DoctrineBridge\DI\TargetEntityProviderInterface;
use SixtyEightPublishers\DoctrineBridge\DI\EntityMappingProviderInterface;
use SixtyEightPublishers\ArchitectureBundle\Bridge\Nette\DI\CompilerExtensionUtilsTrait;
use SixtyEightPublishers\ArchitectureBundle\Bridge\Nette\DI\AutoRegisterDoctrineTypesTrait;
use SixtyEightPublishers\ArchitectureBundle\Bridge\Nette\DI\AutoRegisterDoctrineXmlMappingTrait;
use SixtyEightPublishers\ArchitectureBundle\Bridge\Nette\DI\AutoRegisterDoctrineTargetEntitiesTrait;
use SixtyEightPublishers\ArchitectureBundle\Bridge\Nette\DI\DoctrineInfrastructureExtension as MainDoctrineInfrastructureExtension;

final class DoctrineInfrastructureExtension extends CompilerExtension implements InfrastructureExtensionInterface, DatabaseTypeProviderInterface, TargetEntityProviderInterface, EntityMappingProviderInterface
{
	use CompilerExtensionUtilsTrait;
	use AutoRegisterDoctrineTypesTrait;
	use AutoRegisterDoctrineXmlMappingTrait;
	use AutoRegisterDoctrineTargetEntitiesTrait;

	/**
	 * {@inheritDoc}
	 */
	public function loadConfiguration(): void
	{
		$this->requireCompilerExtension(MainDoctrineInfrastructureExtension::class);
		$this->requireCompilerExtension(UserBundleExtension::class);
		$this->checkCompilerExtensionConcurrency(InfrastructureExtensionInterface::class);
		$this->loadConfigurationDir(__DIR__ . '/config/doctrine_infrastructure');
	}

	/**
	 * @return array
	 */
	public function resolveExtendedAggregates(): array
	{
		$extension = $this->requireCompilerExtension(UserBundleExtension::class);
		assert($extension instanceof UserBundleExtension);

		return $extension->resolveExtendedAggregates();
	}
}
