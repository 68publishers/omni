<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Bridge\Nette\DI;

use Nette\DI\Compiler;
use Nette\Schema\Expect;
use Nette\Schema\Schema;
use Nette\DI\CompilerExtension;
use Fmasa\Messenger\DI\BusConfig;
use Nette\PhpGenerator\ClassType;
use Nette\DI\Definitions\Statement;
use Fmasa\Messenger\DI\TransportConfig;
use Fmasa\Messenger\DI\SerializerConfig;
use Fmasa\Messenger\DI\MessengerExtension;
use Symfony\Component\Messenger\Middleware\AddBusNameStampMiddleware;
use Symfony\Component\Messenger\Middleware\DispatchAfterCurrentBusMiddleware;
use Symfony\Component\Messenger\Middleware\FailedMessageProcessingMiddleware;
use SixtyEightPublishers\ArchitectureBundle\Bridge\Symfony\Messenger\Middleware\StoreTransactionMiddleware;
use SixtyEightPublishers\ArchitectureBundle\Bridge\Symfony\Messenger\Middleware\OriginalExceptionMiddleware;
use SixtyEightPublishers\ArchitectureBundle\Bridge\Symfony\Messenger\Middleware\StorePingConnectionMiddleware;
use SixtyEightPublishers\ArchitectureBundle\Bridge\Symfony\Messenger\Middleware\StoreCloseConnectionMiddleware;

final class ConfiguredMessengerExtension extends CompilerExtension
{
	use CompilerExtensionUtilsTrait;

	public const COMMAND_BUS_NAME = 'command_bus';
	public const QUERY_BUS_NAME = 'query_bus';
	public const EVENT_BUS_NAME = 'event_bus';

	private MessengerExtension $extension;

	public function __construct()
	{
		$this->extension = new MessengerExtension();
	}

	/**
	 * {@inheritDoc}
	 */
	public function getConfigSchema(): Schema
	{
		$debugMode = $this->getContainerBuilder()->parameters['debugMode'] ?? FALSE;

		return Expect::structure([
			'serializer' => Expect::from(new SerializerConfig()),
			'buses' => Expect::arrayOf(Expect::from(new BusConfig()))->default([
				self::COMMAND_BUS_NAME => (object) [
					'allowNoHandlers' => FALSE,
					'singleHandlerPerMessage' => TRUE,
					'middleware' => [
						new Statement(AddBusNameStampMiddleware::class, [self::COMMAND_BUS_NAME]),
						new Statement(FailedMessageProcessingMiddleware::class),
						new Statement(OriginalExceptionMiddleware::class),
						new Statement(DispatchAfterCurrentBusMiddleware::class),
						new Statement(StorePingConnectionMiddleware::class),
						new Statement(StoreCloseConnectionMiddleware::class),
						new Statement(StoreTransactionMiddleware::class),
					],
					'panel' => $debugMode,
				],
				self::QUERY_BUS_NAME => (object) [
					'allowNoHandlers' => FALSE,
					'singleHandlerPerMessage' => TRUE,
					'middleware' => [
						new Statement(AddBusNameStampMiddleware::class, [self::QUERY_BUS_NAME]),
						new Statement(FailedMessageProcessingMiddleware::class),
						new Statement(OriginalExceptionMiddleware::class),
					],
					'panel' => $debugMode,
				],
				self::EVENT_BUS_NAME => (object) [
					'allowNoHandlers' => TRUE,
					'singleHandlerPerMessage' => FALSE,
					'middleware' => [
						new Statement(AddBusNameStampMiddleware::class, [self::EVENT_BUS_NAME]),
						new Statement(FailedMessageProcessingMiddleware::class),
						new Statement(OriginalExceptionMiddleware::class),
					],
					'panel' => $debugMode,
				],
			])->mergeDefaults(TRUE),
			'transports' => Expect::arrayOf(Expect::anyOf(
				Expect::string(),
				Expect::from(new TransportConfig())
			)),
			'failureTransport' => Expect::string()->nullable(),
			'routing' => Expect::arrayOf(
				Expect::anyOf(Expect::string(), Expect::listOf(Expect::string()))
			),
		]);
	}

	/**
	 * {@inheritDoc}
	 */
	public function loadConfiguration(): void
	{
		$this->extension->loadConfiguration();
		$this->loadConfigurationDir(__DIR__ . '/config/configured_messenger');
	}

	/**
	 * {@inheritDoc}
	 *
	 * @throws \Fmasa\Messenger\Exceptions\InvalidHandlerService
	 * @throws \Fmasa\Messenger\Exceptions\MultipleHandlersFound
	 */
	public function beforeCompile(): void
	{
		$this->extension->beforeCompile();
	}

	/**
	 * {@inheritDoc}
	 */
	public function afterCompile(ClassType $class): void
	{
		$this->extension->afterCompile($class);
	}

	/**
	 * {@inheritDoc}
	 */
	public function setCompiler(Compiler $compiler, string $name): self
	{
		$this->extension->setCompiler($compiler, $name);

		return parent::setCompiler($compiler, $name);
	}

	/**
	 * {@inheritDoc}
	 */
	public function setConfig($config): self
	{
		$this->extension->setConfig($config);

		return parent::setConfig($config);
	}
}
