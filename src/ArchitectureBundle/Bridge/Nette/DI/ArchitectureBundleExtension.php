<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Bridge\Nette\DI;

use Nette\Bridges\ApplicationDI\ApplicationExtension;
use Nette\DI\Compiler;
use Nette\DI\CompilerExtension;
use Nette\DI\Definitions\Reference;
use Nette\DI\Definitions\ServiceDefinition;
use Nette\Schema\Expect;
use Nette\Schema\Schema;
use SixtyEightPublishers\ArchitectureBundle\Bridge\Nette\DI\Config\ArchitectureBundleConfig;
use SixtyEightPublishers\ArchitectureBundle\Bridge\Nette\DI\Messenger\MessageBusConfiguration;
use SixtyEightPublishers\ArchitectureBundle\Bridge\Nette\DI\Messenger\MessageBusConfigurationsProviderInterface;
use SixtyEightPublishers\ArchitectureBundle\Bridge\Nette\DI\Messenger\MessengerExtensionDecorator;
use SixtyEightPublishers\ArchitectureBundle\Command\CommandHandlerInterface;
use SixtyEightPublishers\ArchitectureBundle\Event\EventHandlerInterface;
use SixtyEightPublishers\ArchitectureBundle\ReadModel\Query\QueryHandlerInterface;
use function assert;
use function sprintf;

final class ArchitectureBundleExtension extends CompilerExtension implements MessageBusConfigurationsProviderInterface
{
    use CompilerExtensionUtilsTrait;

    public const COMMAND_BUS_NAME = 'command_bus';
    public const QUERY_BUS_NAME = 'query_bus';
    public const EVENT_BUS_NAME = 'event_bus';

    public const EXTENSION_POSTFIX_MESSENGER = 'messenger';

    public function getConfigSchema(): Schema
    {
        return Expect::structure([
            'default_event_store_name' => Expect::string('null')->nullable(),
        ])->castTo(ArchitectureBundleConfig::class);
    }

    public function setCompiler(Compiler $compiler, string $name): static
    {
        parent::setCompiler($compiler, $name);

        $compiler->addExtension(sprintf('%s.%s', $name, self::EXTENSION_POSTFIX_MESSENGER), new MessengerExtensionDecorator());

        return $this;
    }

    public function loadConfiguration(): void
    {
        $this->requireCompilerExtension(InfrastructureExtensionInterface::class);

        $config = $this->getConfig();
        assert($config instanceof ArchitectureBundleConfig);

        $this->setBundleParameter('extension_name', $this->name);
        $this->setBundleParameter('default_event_store_name', $config->default_event_store_name ?? 'null');
        $this->loadConfigurationDir(__DIR__ . '/definitions/architecture_bundle', false);

        if (null !== $this->requireCompilerExtension(ApplicationExtension::class, false)) {
            $this->loadConfigurationDir(__DIR__ . '/definitions/architecture_bundle/http_link');
        }
    }

    public function provideMessageBusConfigurations(): iterable
    {
        yield MessageBusConfiguration::fromFile(self::COMMAND_BUS_NAME, __DIR__ . '/definitions/message_bus/command_bus.neon', [CommandHandlerInterface::class]);
        yield MessageBusConfiguration::fromFile(self::QUERY_BUS_NAME, __DIR__ . '/definitions/message_bus/query_bus.neon', [QueryHandlerInterface::class]);
        yield MessageBusConfiguration::fromFile(self::EVENT_BUS_NAME, __DIR__ . '/definitions/message_bus/event_bus.neon', [EventHandlerInterface::class]);
    }

    public function addEventStore(string $name, Reference $reference): void
    {
        $resolvableEventStore = $this->getContainerBuilder()->getDefinition($this->prefix('event_store.resolvable'));
        assert($resolvableEventStore instanceof ServiceDefinition);

        $eventStores = $resolvableEventStore->getFactory()->arguments['eventStores'] ?? [];
        $eventStores[$name] = $reference;

        $resolvableEventStore->getFactory()->arguments['eventStores'] = $eventStores;
    }

    public function addPersistenceAdapter(Reference $reference): void
    {
        $persistenceAdapterStack = $this->getContainerBuilder()->getDefinition($this->prefix('infrastructure.persistence_adapter.stack'));
        assert($persistenceAdapterStack instanceof ServiceDefinition);

        $adapters = $persistenceAdapterStack->getFactory()->arguments['persistenceAdapters'] ?? [];
        $adapters[] = $reference;

        $persistenceAdapterStack->getFactory()->arguments['persistenceAdapters'] = $adapters;
    }

    public function resolveEventStoreForAggregateClassname(string $aggregateClassname, string $eventStoreName): void
    {
        $eventStoreNameResolver = $this->getContainerBuilder()->getDefinition($this->prefix('event_store.name_resolver'));
        assert($eventStoreNameResolver instanceof ServiceDefinition);

        $eventStoreNameResolver->addSetup('registerAggregateClassname', [
            $aggregateClassname,
            $eventStoreName,
        ]);
    }

    /**
     * @param array<string, mixed> $staticParameters
     */
    public function registerHttpLink(string $name, string $destination, array $staticParameters = []): void
    {
        $linkFactoryDefinition = $this->getContainerBuilder()->getDefinition($this->prefix('application.http_link.link_factory.default'));
        assert($linkFactoryDefinition instanceof ServiceDefinition);

        $linkFactoryDefinition->addSetup('registerLink', [
            $name,
            $destination,
            $staticParameters,
        ]);
    }
}
