<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\Application\CommandHandler;

use SixtyEightPublishers\UserBundle\Domain\Dto\UserId;
use SixtyEightPublishers\UserBundle\Domain\Command\DeleteUserCommand;
use SixtyEightPublishers\ArchitectureBundle\Command\CommandHandlerInterface;
use SixtyEightPublishers\UserBundle\Domain\Repository\UserRepositoryInterface;
use SixtyEightPublishers\ArchitectureBundle\Domain\Guard\CommandConsistencyGuardInterface;

final class DeleteUserCommandHandler implements CommandHandlerInterface
{
	private UserRepositoryInterface $userRepository;

	private CommandConsistencyGuardInterface $commandConsistencyGuard;

	/**
	 * @param \SixtyEightPublishers\UserBundle\Domain\Repository\UserRepositoryInterface             $userRepository
	 * @param \SixtyEightPublishers\ArchitectureBundle\Domain\Guard\CommandConsistencyGuardInterface $commandConsistencyGuard
	 */
	public function __construct(UserRepositoryInterface $userRepository, CommandConsistencyGuardInterface $commandConsistencyGuard)
	{
		$this->userRepository = $userRepository;
		$this->commandConsistencyGuard = $commandConsistencyGuard;
	}

	/**
	 * @param \SixtyEightPublishers\UserBundle\Domain\Command\DeleteUserCommand $command
	 *
	 * @return void
	 */
	public function __invoke(DeleteUserCommand $command): void
	{
		($this->commandConsistencyGuard)($command);

		$user = $this->userRepository->get(UserId::fromString($command->userId()));
		$user->delete();

		$this->userRepository->save($user);
	}
}
