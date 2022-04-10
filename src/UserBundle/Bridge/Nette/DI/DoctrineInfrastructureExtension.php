<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\Bridge\Nette\DI;

use Nette\DI\CompilerExtension;
use SixtyEightPublishers\DoctrineBridge\DI\TargetEntity;
use SixtyEightPublishers\UserBundle\Domain\Aggregate\User;
use SixtyEightPublishers\DoctrineBridge\DI\DatabaseTypeProviderInterface;
use SixtyEightPublishers\DoctrineBridge\DI\TargetEntityProviderInterface;
use SixtyEightPublishers\DoctrineBridge\DI\EntityMappingProviderInterface;
use SixtyEightPublishers\ArchitectureBundle\Bridge\Nette\DI\CompilerExtensionUtilsTrait;
use SixtyEightPublishers\ArchitectureBundle\Bridge\Nette\DI\AutoRegisterDoctrineTypesTrait;
use SixtyEightPublishers\ArchitectureBundle\Bridge\Nette\DI\AutoRegisterDoctrineXmlMappingTrait;
use SixtyEightPublishers\ArchitectureBundle\Bridge\Nette\DI\DoctrineInfrastructureExtension as MainDoctrineInfrastructureExtension;

final class DoctrineInfrastructureExtension extends CompilerExtension implements InfrastructureExtensionInterface, DatabaseTypeProviderInterface, TargetEntityProviderInterface, EntityMappingProviderInterface
{
	use CompilerExtensionUtilsTrait;
	use AutoRegisterDoctrineTypesTrait;
	use AutoRegisterDoctrineXmlMappingTrait;

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
	 * {@inheritDoc}
	 */
	public function getTargetEntities(): array
	{
		$userBundleExtension = $this->requireCompilerExtension(UserBundleExtension::class);
		assert($userBundleExtension instanceof UserBundleExtension);

		$classname = $userBundleExtension->getConfig()->entity_classname->user;

		if (User::class === $classname) {
			return [];
		}

		return [
			new TargetEntity(User::class, $classname),
		];
	}
}
