<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Bridge\Symfony\Messenger\Bus;

use Symfony\Component\Messenger\MessageBusInterface;
use SixtyEightPublishers\ArchitectureBundle\Event\EventInterface;
use SixtyEightPublishers\ArchitectureBundle\Bus\EventBusInterface;

final class EventBus implements EventBusInterface
{
	private MessageBusInterface $messageBus;

	/**
	 * @param \Symfony\Component\Messenger\MessageBusInterface $messageBus
	 */
	public function __construct(MessageBusInterface $messageBus)
	{
		$this->messageBus = $messageBus;
	}

	/**
	 * {@inheritDoc}
	 */
	public function dispatch(EventInterface $message, array $stamps = []): void
	{
		$this->messageBus->dispatch($message, $stamps);
	}
}
