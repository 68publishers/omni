<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Bridge\Symfony\Messenger\Bus;

use SixtyEightPublishers\ArchitectureBundle\Bus\EventBusInterface;
use SixtyEightPublishers\ArchitectureBundle\Event\EventInterface;
use Symfony\Component\Messenger\MessageBusInterface;

final class EventBus implements EventBusInterface
{
    public function __construct(
        private readonly MessageBusInterface $messageBus,
    ) {}

    public function dispatch(EventInterface $message, array $stamps = []): void
    {
        $this->messageBus->dispatch($message, $stamps);
    }
}
