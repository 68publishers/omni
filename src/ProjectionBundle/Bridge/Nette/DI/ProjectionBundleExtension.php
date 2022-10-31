<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ProjectionBundle\Bridge\Nette\DI;

use Nette\Schema\Expect;
use Nette\Schema\Schema;
use Nette\DI\CompilerExtension;
use Nette\DI\Definitions\Statement;
use Nette\DI\InvalidConfigurationException;
use SixtyEightPublishers\ProjectionBundle\Projection\ProjectionInterface;
use SixtyEightPublishers\ArchitectureBundle\Bridge\Nette\DI\CompilerExtensionUtilsTrait;
use SixtyEightPublishers\ArchitectureBundle\Bridge\Nette\DI\Messenger\MessageBusConfiguration;
use SixtyEightPublishers\ProjectionBundle\Bridge\Symfony\Messenger\Transport\EventStoreReceiver;
use SixtyEightPublishers\ArchitectureBundle\Bridge\Nette\DI\Messenger\MessageBusConfigurationsProviderInterface;

final class ProjectionBundleExtension extends CompilerExtension implements MessageBusConfigurationsProviderInterface
{
	use CompilerExtensionUtilsTrait;

	public const PROJECTION_BUS_NAME = 'projection_bus';

	public function getConfigSchema(): Schema
	{
		return Expect::listOf('string|' . Statement::class);
	}

	public function loadConfiguration(): void
	{
		$this->requireCompilerExtension(InfrastructureExtensionInterface::class);

		$builder = $this->getContainerBuilder();

		foreach ($this->getConfig() as $i => $projection) {
			$classname = $projection;

			if ($classname instanceof Statement) {
				$classname = $classname->getEntity();
			}

			if (!is_a($classname, ProjectionInterface::class, TRUE)) {
				throw new InvalidConfigurationException(sprintf(
					'Projection "%s" not found or is not implementor of %s interface.',
					$classname,
					ProjectionInterface::class
				));
			}

			$builder->addDefinition($this->prefix('projection.' . $i))
				->setType($classname)
				->setFactory($projection)
				->setAutowired(FALSE)
				->addTag('messenger.messageHandler', [
					'bus' => self::PROJECTION_BUS_NAME,
				]);

			$builder->addDefinition($this->prefix('receiver.' . $i))
				->setType(EventStoreReceiver::class)
				->setArgument('projectionClassname', $classname)
				->setAutowired(FALSE)
				->addTag('messenger.receiver.alias', $classname::projectionName());
		}
	}

	public function provideMessageBusConfigurations(): iterable
	{
		yield MessageBusConfiguration::fromFile(self::PROJECTION_BUS_NAME, __DIR__ . '/config/message_bus/projection_bus.neon');
	}
}
