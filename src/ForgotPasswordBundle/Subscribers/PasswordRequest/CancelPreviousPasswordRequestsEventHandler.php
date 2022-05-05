<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ForgotPasswordBundle\Subscribers\PasswordRequest;

use SixtyEightPublishers\ArchitectureBundle\Bus\QueryBusInterface;
use SixtyEightPublishers\ArchitectureBundle\ReadModel\Query\Batch;
use SixtyEightPublishers\ArchitectureBundle\Bus\CommandBusInterface;
use SixtyEightPublishers\ArchitectureBundle\Event\EventHandlerInterface;
use SixtyEightPublishers\ForgotPasswordBundle\ReadModel\View\PasswordRequestView;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\Event\PasswordChangeRequested;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\Command\CancelPasswordRequestCommand;
use SixtyEightPublishers\ForgotPasswordBundle\ReadModel\Query\FindRequestedPasswordChangesQuery;

final class CancelPreviousPasswordRequestsEventHandler implements EventHandlerInterface
{
	private QueryBusInterface $queryBus;

	private CommandBusInterface $commandBus;

	/**
	 * @param \SixtyEightPublishers\ArchitectureBundle\Bus\QueryBusInterface   $queryBus
	 * @param \SixtyEightPublishers\ArchitectureBundle\Bus\CommandBusInterface $commandBus
	 */
	public function __construct(QueryBusInterface $queryBus, CommandBusInterface $commandBus)
	{
		$this->queryBus = $queryBus;
		$this->commandBus = $commandBus;
	}

	/**
	 * @param \SixtyEightPublishers\ForgotPasswordBundle\Domain\Event\PasswordChangeRequested $event
	 *
	 * @return void
	 */
	public function __invoke(PasswordChangeRequested $event): void
	{
		foreach ($this->queryBus->dispatch(FindRequestedPasswordChangesQuery::create($event->emailAddress()->value())) as $batch) {
			assert($batch instanceof Batch);

			foreach ($batch as $item) {
				assert($item instanceof PasswordRequestView);

				if ($event->passwordRequestId()->equals($item->id)) {
					continue;
				}

				$deviceInfo = $event->requestDeviceInfo();

				$this->commandBus->dispatch(CancelPasswordRequestCommand::create(
					$item->id->toString(),
					$deviceInfo->ipAddress()->value(),
					$deviceInfo->userAgent()->value()
				));
			}
		}
	}
}
