<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ProjectionBundle\Bridge\Nette\DI;

use Nette\Schema\Expect;
use Nette\Schema\Schema;
use Nette\DI\CompilerExtension;
use SixtyEightPublishers\ArchitectureBundle\Bridge\Nette\DI\CompilerExtensionUtilsTrait;

final class DoctrineInfrastructureExtension extends CompilerExtension implements InfrastructureExtensionInterface
{
	use CompilerExtensionUtilsTrait;

	public function getConfigSchema(): Schema
	{
		return Expect::structure([
			'projection_table_name' => Expect::string('projection'),
		]);
	}

	public function loadConfiguration(): void
	{
		$this->requireCompilerExtension(ProjectionBundleExtension::class);
		$this->setBundleParameter('projection_table_name', $this->config->projection_table_name, 'projection_bundle_doctrine');
		$this->loadConfigurationDir(__DIR__ . '/config/doctrine_infrastructure');
	}
}
