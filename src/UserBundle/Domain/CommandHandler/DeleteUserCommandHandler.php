<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\Domain\CommandHandler;

use SixtyEightPublishers\UserBundle\Domain\ValueObject\UserId;
use SixtyEightPublishers\UserBundle\Domain\Command\DeleteUserCommand;
use SixtyEightPublishers\ArchitectureBundle\Command\CommandHandlerInterface;
use SixtyEightPublishers\UserBundle\Domain\Repository\UserRepositoryInterface;

final class DeleteUserCommandHandler implements CommandHandlerInterface
{
	private UserRepositoryInterface $userRepository;

	/**
	 * @param \SixtyEightPublishers\UserBundle\Domain\Repository\UserRepositoryInterface $userRepository
	 */
	public function __construct(UserRepositoryInterface $userRepository)
	{
		$this->userRepository = $userRepository;
	}

	/**
	 * @param \SixtyEightPublishers\UserBundle\Domain\Command\DeleteUserCommand $command
	 *
	 * @return void
	 */
	public function __invoke(DeleteUserCommand $command): void
	{
		$user = $this->userRepository->get(UserId::fromString($command->userId()));
		$user->delete();

		$this->userRepository->save($user);
	}
}
