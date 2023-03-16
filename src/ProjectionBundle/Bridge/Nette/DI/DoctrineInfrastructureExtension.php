<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ProjectionBundle\Bridge\Nette\DI;

use Nette\DI\CompilerExtension;
use Nette\Schema\Expect;
use Nette\Schema\Schema;
use SixtyEightPublishers\ArchitectureBundle\Bridge\Nette\DI\CompilerExtensionUtilsTrait;
use SixtyEightPublishers\ProjectionBundle\Bridge\Nette\DI\Config\DoctrineInfrastructureConfig;
use function assert;

final class DoctrineInfrastructureExtension extends CompilerExtension implements InfrastructureExtensionInterface
{
    use CompilerExtensionUtilsTrait;

    public function getConfigSchema(): Schema
    {
        return Expect::structure([
            'projection_table_name' => Expect::string('projection'),
        ])->castTo(DoctrineInfrastructureConfig::class);
    }

    public function loadConfiguration(): void
    {
        $config = $this->getConfig();
        assert($config instanceof DoctrineInfrastructureConfig);

        $this->requireCompilerExtension(ProjectionBundleExtension::class);
        $this->setBundleParameter('projection_table_name', $config->projection_table_name, 'projection_bundle_doctrine');
        $this->loadConfigurationDir(__DIR__ . '/definitions/doctrine_infrastructure');
    }
}
