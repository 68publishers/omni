<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ProjectionBundle\Bridge\Nette\DI;

use Nette\DI\CompilerExtension;
use SixtyEightPublishers\ArchitectureBundle\Bridge\Nette\DI\CompilerExtensionUtilsTrait;

final class DoctrineInfrastructureExtension extends CompilerExtension implements InfrastructureExtensionInterface
{
	use CompilerExtensionUtilsTrait;

	public function loadConfiguration(): void
	{
		$this->requireCompilerExtension(ProjectionBundleExtension::class);
		$this->loadConfigurationDir(__DIR__ . '/config/doctrine_infrastructure');
	}
}
