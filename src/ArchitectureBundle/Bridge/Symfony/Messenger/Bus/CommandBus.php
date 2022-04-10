<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Bridge\Symfony\Messenger\Bus;

use Symfony\Component\Messenger\MessageBusInterface;
use SixtyEightPublishers\ArchitectureBundle\Bus\CommandBusInterface;
use SixtyEightPublishers\ArchitectureBundle\Command\CommandInterface;

final class CommandBus implements CommandBusInterface
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
	public function dispatch(CommandInterface $message, array $stamps = []): void
	{
		$this->messageBus->dispatch($message, $stamps);
	}
}
