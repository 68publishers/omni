<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ForgotPasswordBundle\Bridge\Nette\DI;

use Nette\DI\CompilerExtension;
use SixtyEightPublishers\ArchitectureBundle\Bridge\Nette\DI\AutoRegisterDoctrineTypesTrait;
use SixtyEightPublishers\ArchitectureBundle\Bridge\Nette\DI\AutoRegisterDoctrineXmlMappingTrait;
use SixtyEightPublishers\ArchitectureBundle\Bridge\Nette\DI\CompilerExtensionUtilsTrait;
use SixtyEightPublishers\ArchitectureBundle\Bridge\Nette\DI\DoctrineInfrastructureExtension as MainDoctrineInfrastructureExtension;
use SixtyEightPublishers\DoctrineBridge\Bridge\Nette\DI\DatabaseTypeProviderInterface;
use SixtyEightPublishers\DoctrineBridge\Bridge\Nette\DI\EntityMappingProviderInterface;
use SixtyEightPublishers\DoctrineBridge\Bridge\Nette\DI\TargetEntity;
use SixtyEightPublishers\DoctrineBridge\Bridge\Nette\DI\TargetEntityProviderInterface;
use SixtyEightPublishers\ForgotPasswordBundle\Bridge\Nette\DI\Config\ForgotPasswordBundleConfig;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\PasswordRequest;
use function assert;

final class DoctrineInfrastructureExtension extends CompilerExtension implements InfrastructureExtensionInterface, DatabaseTypeProviderInterface, TargetEntityProviderInterface, EntityMappingProviderInterface
{
    use CompilerExtensionUtilsTrait;
    use AutoRegisterDoctrineTypesTrait;
    use AutoRegisterDoctrineXmlMappingTrait;

    public function loadConfiguration(): void
    {
        $this->requireCompilerExtension(MainDoctrineInfrastructureExtension::class);
        $this->requireCompilerExtension(ForgotPasswordBundleExtension::class);
        $this->checkCompilerExtensionConcurrency(InfrastructureExtensionInterface::class);
        $this->loadConfigurationDir(__DIR__ . '/definitions/doctrine_infrastructure');
    }

    public function getTargetEntities(): array
    {
        $extension = $this->requireCompilerExtension(ForgotPasswordBundleExtension::class);
        assert($extension instanceof ForgotPasswordBundleExtension);
        $config = $extension->getConfig();
        assert($config instanceof ForgotPasswordBundleConfig);

        if (PasswordRequest::class === $config->aggregate->password_request->classname) {
            return [];
        }

        return [
            new TargetEntity(PasswordRequest::class, $config->aggregate->password_request->classname),
        ];
    }
}
