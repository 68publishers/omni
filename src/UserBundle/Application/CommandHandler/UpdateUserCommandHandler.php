<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\Application\CommandHandler;

use SixtyEightPublishers\UserBundle\Domain\Dto\UserId;
use SixtyEightPublishers\UserBundle\Domain\Command\UpdateUserCommand;
use SixtyEightPublishers\UserBundle\Domain\PasswordHashAlgorithmInterface;
use SixtyEightPublishers\ArchitectureBundle\Command\CommandHandlerInterface;
use SixtyEightPublishers\UserBundle\Domain\Repository\UserRepositoryInterface;
use SixtyEightPublishers\ArchitectureBundle\Domain\Guard\CommandConsistencyGuardInterface;

final class UpdateUserCommandHandler implements CommandHandlerInterface
{
	private UserRepositoryInterface $userRepository;

	private PasswordHashAlgorithmInterface $algorithm;

	private CommandConsistencyGuardInterface $commandConsistencyGuard;

	/**
	 * @param \SixtyEightPublishers\UserBundle\Domain\Repository\UserRepositoryInterface             $userRepository
	 * @param \SixtyEightPublishers\UserBundle\Domain\PasswordHashAlgorithmInterface                 $algorithm
	 * @param \SixtyEightPublishers\ArchitectureBundle\Domain\Guard\CommandConsistencyGuardInterface $commandConsistencyGuard
	 */
	public function __construct(UserRepositoryInterface $userRepository, PasswordHashAlgorithmInterface $algorithm, CommandConsistencyGuardInterface $commandConsistencyGuard)
	{
		$this->userRepository = $userRepository;
		$this->algorithm = $algorithm;
		$this->commandConsistencyGuard = $commandConsistencyGuard;
	}

	/**
	 * @param \SixtyEightPublishers\UserBundle\Domain\Command\UpdateUserCommand $command
	 *
	 * @return void
	 */
	public function __invoke(UpdateUserCommand $command): void
	{
		($this->commandConsistencyGuard)($command);

		$user = $this->userRepository->get(UserId::fromString($command->userId()));

		$user->update($command, $this->algorithm);

		$this->userRepository->save($user);
	}
}
