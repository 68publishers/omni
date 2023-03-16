<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Bridge\Nette\DI\Messenger;

interface MessageBusConfigurationsProviderInterface
{
    /**
     * @return iterable<MessageBusConfiguration>
     */
    public function provideMessageBusConfigurations(): iterable;
}
