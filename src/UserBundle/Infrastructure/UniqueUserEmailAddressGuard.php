<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\Infrastructure;

use SixtyEightPublishers\UserBundle\Domain\Dto\UserId;
use SixtyEightPublishers\UserBundle\ReadModel\View\UserView;
use SixtyEightPublishers\ArchitectureBundle\Bus\QueryBusInterface;
use SixtyEightPublishers\ArchitectureBundle\Command\CommandInterface;
use SixtyEightPublishers\UserBundle\Domain\Command\CreateUserCommand;
use SixtyEightPublishers\UserBundle\Domain\Command\UpdateUserCommand;
use SixtyEightPublishers\UserBundle\ReadModel\Query\GetUserByEmailAddressQuery;
use SixtyEightPublishers\ArchitectureBundle\Domain\Exception\CommandConsistencyException;

final class UniqueUserEmailAddressGuard implements UserCommandConsistencyGuardInterface
{
	private QueryBusInterface $queryBus;

	/**
	 * @param \SixtyEightPublishers\ArchitectureBundle\Bus\QueryBusInterface $queryBus
	 */
	public function __construct(QueryBusInterface $queryBus)
	{
		$this->queryBus = $queryBus;
	}

	/**
	 * @param \SixtyEightPublishers\ArchitectureBundle\Command\CommandInterface $command
	 *
	 * @return void
	 */
	public function __invoke(CommandInterface $command): void
	{
		if ($command instanceof CreateUserCommand && NULL !== $this->queryBus->dispatch(GetUserByEmailAddressQuery::create($command->emailAddress()))) {
			throw CommandConsistencyException::fromMessage(sprintf(
				'User with email %s already exists.',
				$command->emailAddress()
			));
		}

		if ($command instanceof UpdateUserCommand && NULL !== $command->emailAddress()) {
			$view = $this->queryBus->dispatch(GetUserByEmailAddressQuery::create($command->emailAddress()));

			if ($view instanceof UserView && !$view->id->equals(UserId::fromString($command->userId()))) {
				throw CommandConsistencyException::fromMessage(sprintf(
					'User with email %s already exists.',
					$command->emailAddress()
				));
			}
		}
	}
}
