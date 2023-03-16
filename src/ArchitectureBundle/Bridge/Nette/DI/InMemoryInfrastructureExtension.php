<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Bridge\Nette\DI;

use Nette\DI\CompilerExtension;

final class InMemoryInfrastructureExtension extends CompilerExtension implements InfrastructureExtensionInterface
{
    use CompilerExtensionUtilsTrait;

    public function loadConfiguration(): void
    {
        $this->requireCompilerExtension(ArchitectureBundleExtension::class);
        $this->checkCompilerExtensionConcurrency(InfrastructureExtensionInterface::class);
        $this->loadConfigurationDir(__DIR__ . '/definitions/in_memory_infrastructure');
    }
}
