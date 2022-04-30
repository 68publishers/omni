<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ForgotPasswordBundle\Application\EventHandler;

use SixtyEightPublishers\UserBundle\ReadModel\View\UserView;
use SixtyEightPublishers\ArchitectureBundle\Bus\QueryBusInterface;
use SixtyEightPublishers\ArchitectureBundle\Bus\CommandBusInterface;
use SixtyEightPublishers\UserBundle\Domain\Command\UpdateUserCommand;
use SixtyEightPublishers\ArchitectureBundle\Event\EventHandlerInterface;
use SixtyEightPublishers\UserBundle\ReadModel\Query\GetUserByEmailAddressQuery;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\Event\PasswordChangeCompleted;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\Exception\EmailAddressNotFoundException;

final class ChangePasswordOnPasswordRequestCompletedEventHandler implements EventHandlerInterface
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
	 * @param \SixtyEightPublishers\ForgotPasswordBundle\Domain\Event\PasswordChangeCompleted $event
	 *
	 * @return void
	 */
	public function __invoke(PasswordChangeCompleted $event): void
	{
		$userView = $this->queryBus->dispatch(GetUserByEmailAddressQuery::create($event->emailAddress()->value()));

		if (!$userView instanceof UserView) {
			throw EmailAddressNotFoundException::create($event->emailAddress()->value());
		}

		$command = UpdateUserCommand::create($userView->id->toString())
			->withPassword($event->password());

		$this->commandBus->dispatch($command);
	}
}
