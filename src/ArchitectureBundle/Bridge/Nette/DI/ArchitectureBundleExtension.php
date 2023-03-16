<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Bridge\Nette\DI;

use Nette\DI\Compiler;
use Nette\DI\CompilerExtension;
use SixtyEightPublishers\ArchitectureBundle\Bridge\Nette\DI\Messenger\MessageBusConfiguration;
use SixtyEightPublishers\ArchitectureBundle\Bridge\Nette\DI\Messenger\MessageBusConfigurationsProviderInterface;
use SixtyEightPublishers\ArchitectureBundle\Bridge\Nette\DI\Messenger\MessengerExtensionDecorator;
use SixtyEightPublishers\ArchitectureBundle\Command\CommandHandlerInterface;
use SixtyEightPublishers\ArchitectureBundle\Event\EventHandlerInterface;
use SixtyEightPublishers\ArchitectureBundle\ReadModel\Query\QueryHandlerInterface;
use function sprintf;

final class ArchitectureBundleExtension extends CompilerExtension implements MessageBusConfigurationsProviderInterface
{
    use CompilerExtensionUtilsTrait;

    public const COMMAND_BUS_NAME = 'command_bus';
    public const QUERY_BUS_NAME = 'query_bus';
    public const EVENT_BUS_NAME = 'event_bus';

    public const EXTENSION_POSTFIX_MESSENGER = 'messenger';

    public function setCompiler(Compiler $compiler, string $name): self
    {
        parent::setCompiler($compiler, $name);

        $compiler->addExtension(sprintf('%s.%s', $name, self::EXTENSION_POSTFIX_MESSENGER), new MessengerExtensionDecorator());

        return $this;
    }

    public function loadConfiguration(): void
    {
        $this->requireCompilerExtension(InfrastructureExtensionInterface::class);
        $this->setBundleParameter('extension_name', $this->name);
        $this->loadConfigurationDir(__DIR__ . '/definitions/architecture_bundle');
    }

    public function provideMessageBusConfigurations(): iterable
    {
        yield MessageBusConfiguration::fromFile(self::COMMAND_BUS_NAME, __DIR__ . '/definitions/message_bus/command_bus.neon', [CommandHandlerInterface::class]);
        yield MessageBusConfiguration::fromFile(self::QUERY_BUS_NAME, __DIR__ . '/definitions/message_bus/query_bus.neon', [QueryHandlerInterface::class]);
        yield MessageBusConfiguration::fromFile(self::EVENT_BUS_NAME, __DIR__ . '/definitions/message_bus/event_bus.neon', [EventHandlerInterface::class]);
    }
}
