<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Bridge\Nette\DI;

use Nette\DI\CompilerExtension;
use Nette\DI\Definitions\Reference;
use SixtyEightPublishers\ArchitectureBundle\Infrastructure\InMemory\EventStore\InMemoryEventStore;
use function assert;

final class InMemoryInfrastructureExtension extends CompilerExtension implements InfrastructureExtensionInterface
{
    use CompilerExtensionUtilsTrait;

    public function loadConfiguration(): void
    {
        $this->requireCompilerExtension(ArchitectureBundleExtension::class);
        $this->loadConfigurationDir(__DIR__ . '/definitions/in_memory_infrastructure');
    }

    public function beforeCompile(): void
    {
        $architectureBundle = $this->requireCompilerExtension(ArchitectureBundleExtension::class);
        assert($architectureBundle instanceof ArchitectureBundleExtension);

        $architectureBundle->addEventStore(InMemoryEventStore::NAME, new Reference($this->prefix('infrastructure.event_store.in_memory')));
    }
}
