<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Bridge\Nette\DI\Messenger;

interface MessageBusConfigurationsProviderInterface
{
	/**
	 * @return iterable|\SixtyEightPublishers\ArchitectureBundle\Bridge\Nette\DI\Messenger\MessageBusConfiguration[]
	 */
	public function provideMessageBusConfigurations(): iterable;
}
