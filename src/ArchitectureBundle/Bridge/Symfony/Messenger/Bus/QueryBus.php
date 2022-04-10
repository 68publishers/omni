<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Bridge\Symfony\Messenger\Bus;

use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use SixtyEightPublishers\ArchitectureBundle\Bus\QueryBusInterface;
use SixtyEightPublishers\ArchitectureBundle\ReadModel\Query\QueryInterface;

final class QueryBus implements QueryBusInterface
{
	use HandleTrait;

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
	public function dispatch(QueryInterface $message)
	{
		return $this->handle($message);
	}
}
