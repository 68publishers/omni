<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ForgotPasswordBundle\Bridge\Nette\DI;

use Nette\DI\CompilerExtension;
use SixtyEightPublishers\ArchitectureBundle\Bridge\Nette\DI\AutoRegisterDoctrineTargetEntitiesTrait;
use SixtyEightPublishers\ArchitectureBundle\Bridge\Nette\DI\AutoRegisterDoctrineTypesTrait;
use SixtyEightPublishers\ArchitectureBundle\Bridge\Nette\DI\AutoRegisterDoctrineXmlMappingTrait;
use SixtyEightPublishers\ArchitectureBundle\Bridge\Nette\DI\CompilerExtensionUtilsTrait;
use SixtyEightPublishers\ArchitectureBundle\Bridge\Nette\DI\DoctrineInfrastructureExtension as MainDoctrineInfrastructureExtension;
use SixtyEightPublishers\DoctrineBridge\Bridge\Nette\DI\DatabaseTypeProviderInterface;
use SixtyEightPublishers\DoctrineBridge\Bridge\Nette\DI\EntityMappingProviderInterface;
use SixtyEightPublishers\DoctrineBridge\Bridge\Nette\DI\TargetEntityProviderInterface;
use function assert;

final class DoctrineInfrastructureExtension extends CompilerExtension implements InfrastructureExtensionInterface, DatabaseTypeProviderInterface, TargetEntityProviderInterface, EntityMappingProviderInterface
{
    use CompilerExtensionUtilsTrait;
    use AutoRegisterDoctrineTypesTrait;
    use AutoRegisterDoctrineXmlMappingTrait;
    use AutoRegisterDoctrineTargetEntitiesTrait;

    public function loadConfiguration(): void
    {
        $this->requireCompilerExtension(MainDoctrineInfrastructureExtension::class);
        $this->requireCompilerExtension(ForgotPasswordBundleExtension::class);
        $this->checkCompilerExtensionConcurrency(InfrastructureExtensionInterface::class);
        $this->loadConfigurationDir(__DIR__ . '/definitions/doctrine_infrastructure');
    }

    /**
     * @return array<class-string, class-string>
     */
    public function resolveExtendedAggregates(): array
    {
        $extension = $this->requireCompilerExtension(ForgotPasswordBundleExtension::class);
        assert($extension instanceof ForgotPasswordBundleExtension);

        return $extension->resolveExtendedAggregates();
    }
}
