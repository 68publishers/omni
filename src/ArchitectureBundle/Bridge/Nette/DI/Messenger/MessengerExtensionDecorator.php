<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Bridge\Nette\DI\Messenger;

use Fmasa\Messenger\DI\MessengerExtension;
use Fmasa\Messenger\Exceptions\InvalidHandlerService;
use Fmasa\Messenger\Exceptions\MultipleHandlersFound;
use Nette\DI\Compiler;
use Nette\DI\CompilerExtension;
use Nette\DI\Definitions\Definition;
use Nette\DI\Definitions\FactoryDefinition;
use Nette\DI\Extensions\ParametersExtension;
use Nette\DI\Helpers;
use Nette\DI\InvalidConfigurationException;
use Nette\PhpGenerator\ClassType;
use Nette\Schema\Context;
use Nette\Schema\Expect;
use Nette\Schema\Processor;
use Nette\Schema\Schema;
use Nette\Schema\ValidationException;
use SixtyEightPublishers\ArchitectureBundle\Bridge\Nette\DI\CompilerExtensionUtilsTrait;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use function array_filter;
use function assert;
use function is_a;
use function is_array;
use function is_string;
use function trigger_error;

final class MessengerExtensionDecorator extends CompilerExtension
{
    use CompilerExtensionUtilsTrait;

    private MessengerExtension $extension;

    public function __construct()
    {
        $this->extension = new MessengerExtension();
    }

    public function getConfigSchema(): Schema
    {
        return Expect::structure([])->otherItems();
    }

    public function loadConfiguration(): void
    {
        $configs = [];

        foreach ($this->getMessageBusConfigurations() as $messageBusConfiguration) {
            $configuration = $messageBusConfiguration->configuration;

            if (is_string($configuration)) {
                $configuration = Helpers::expand(
                    $this->loadFromFile($configuration),
                    $this->getContainerBuilder()->parameters,
                    true,
                );
            }

            $configs[] = [
                'buses' => [
                    $messageBusConfiguration->busName => $configuration,
                ],
            ];
        }

        $configs[] = $this->getConfig();

        $this->extension->setConfig(
            $this->processSchema($this->extension->getConfigSchema(), $configs, $this->name),
        );
        $this->extension->loadConfiguration();
        $this->loadConfigurationDir(__DIR__ . '/../definitions/messenger_extension_decorator');
    }

    /**
     * @throws MultipleHandlersFound
     * @throws InvalidHandlerService
     */
    public function beforeCompile(): void
    {
        foreach ($this->getMessageBusConfigurations() as $messageBusConfiguration) {
            foreach ($messageBusConfiguration->messageHandlerTypes as $messageHandlerType) {
                $this->setupMessageHandlerTag($messageHandlerType, $messageBusConfiguration->busName);
            }
        }

        $this->extension->beforeCompile();
    }

    public function afterCompile(ClassType $class): void
    {
        $this->extension->afterCompile($class);
    }

    public function setCompiler(Compiler $compiler, string $name): self
    {
        $this->extension->setCompiler($compiler, $name);

        return parent::setCompiler($compiler, $name);
    }

    /**
     * @return iterable<MessageBusConfiguration>
     */
    public function getMessageBusConfigurations(): iterable
    {
        foreach ($this->compiler->getExtensions(MessageBusConfigurationsProviderInterface::class) as $extension) {
            assert($extension instanceof MessageBusConfigurationsProviderInterface);

            yield from $extension->provideMessageBusConfigurations();
        }
    }

    /**
     * Nette\DI\Compiler::processSchema()
     *
     * @param array<int, mixed> $configs
     */
    private function processSchema(Schema $schema, array $configs, ?string $name = null): mixed
    {
        $processor = new Processor();
        $processor->onNewContext[] = function (Context $context) use ($name) {
            $context->path = $name ? [$name] : [];
            $context->dynamics = &$this->requireCompilerExtension(ParametersExtension::class)->dynamicValidators;
        };
        try {
            $res = $processor->processMultiple($schema, $configs);
        } catch (ValidationException $e) {
            throw new InvalidConfigurationException($e->getMessage());
        }

        foreach ($processor->getWarnings() as $warning) {
            trigger_error($warning, E_USER_DEPRECATED);
        }

        return $res;
    }

    /**
     * @param class-string<MessageHandlerInterface> $messageHandlerType
     */
    private function setupMessageHandlerTag(string $messageHandlerType, string $busName): void
    {
        foreach ($this->findByType($messageHandlerType) as $definition) {
            $tag = $definition->getTag('messenger.messageHandler') ?? [];

            if (!is_array($tag)) {
                $tag = [];
            }

            if (!isset($tag['bus'])) {
                $tag['bus'] = $busName;
            }

            $definition->addTag('messenger.messageHandler', $tag);
        }
    }

    /**
     * @return array<Definition>
     */
    private function findByType(string $type): array
    {
        return array_filter($this->getContainerBuilder()->getDefinitions(), static function (Definition $definition) use ($type): bool {
            return null !== $definition->getType()
                && (
                    is_a($definition->getType(), $type, true)
                    || (
                        $definition instanceof FactoryDefinition
                        && null !== $definition->getResultType()
                        && is_a(
                            $definition->getResultType(),
                            $type,
                            true,
                        )
                    )
                );
        });
    }
}
