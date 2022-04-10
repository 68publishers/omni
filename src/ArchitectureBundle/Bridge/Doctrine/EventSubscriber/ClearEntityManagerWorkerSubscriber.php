<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Bridge\Doctrine\EventSubscriber;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Messenger\Event\WorkerMessageFailedEvent;
use Symfony\Component\Messenger\Event\WorkerMessageHandledEvent;

final class ClearEntityManagerWorkerSubscriber implements EventSubscriberInterface
{
	private ManagerRegistry $managerRegistry;

	/**
	 * @param \Doctrine\Persistence\ManagerRegistry $managerRegistry
	 */
	public function __construct(ManagerRegistry $managerRegistry)
	{
		$this->managerRegistry = $managerRegistry;
	}

	/**
	 * {@inheritDoc}
	 */
	public static function getSubscribedEvents(): iterable
	{
		yield WorkerMessageHandledEvent::class => 'onWorkerMessageHandled';
		yield WorkerMessageFailedEvent::class => 'onWorkerMessageFailed';
	}

	/**
	 * @return void
	 */
	public function onWorkerMessageHandled(): void
	{
		$this->clearEntityManagers();
	}

	/**
	 * @return void
	 */
	public function onWorkerMessageFailed(): void
	{
		$this->clearEntityManagers();
	}

	/**
	 * @return void
	 */
	private function clearEntityManagers(): void
	{
		foreach ($this->managerRegistry->getManagers() as $manager) {
			$manager->clear();
		}
	}
}
