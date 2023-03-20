<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Bridge\Nette\DI;

use Nette\DI\CompilerExtension;
use Nette\DI\Definitions\Reference;
use SixtyEightPublishers\ArchitectureBundle\Infrastructure\Doctrine\EventStore\DoctrineEventStore;
use SixtyEightPublishers\DoctrineBridge\Bridge\Nette\DI\DatabaseTypeProviderInterface;
use SixtyEightPublishers\DoctrineBridge\Bridge\Nette\DI\DoctrineBridgeExtension;
use function assert;

final class DoctrineInfrastructureExtension extends CompilerExtension implements InfrastructureExtensionInterface, DatabaseTypeProviderInterface
{
    use CompilerExtensionUtilsTrait;
    use AutoRegisterDoctrineTypesTrait;

    public const DOCTRINE_PLATFORM_ALIAS = '68publishers.doctrine_platform';

    public function loadConfiguration(): void
    {
        $this->requireCompilerExtension(ArchitectureBundleExtension::class);
        $this->requireCompilerExtension(DoctrineBridgeExtension::class);
        $this->loadConfigurationDir(__DIR__ . '/definitions/doctrine_infrastructure');

        $this->getContainerBuilder()->addAlias(self::DOCTRINE_PLATFORM_ALIAS, $this->prefix('infrastructure.platform'));
    }

    public function beforeCompile(): void
    {
        $architectureBundle = $this->requireCompilerExtension(ArchitectureBundleExtension::class);
        assert($architectureBundle instanceof ArchitectureBundleExtension);

        $architectureBundle->addEventStore(DoctrineEventStore::NAME, new Reference($this->prefix('infrastructure.event_store.doctrine')));
        $architectureBundle->addPersistenceAdapter(new Reference($this->prefix('infrastructure.persistence_adapter.doctrine')));
    }
}
