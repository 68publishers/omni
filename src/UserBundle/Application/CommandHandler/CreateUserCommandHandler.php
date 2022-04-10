<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\Application\CommandHandler;

use SixtyEightPublishers\UserBundle\Domain\Command\CreateUserCommand;
use SixtyEightPublishers\UserBundle\Domain\PasswordHashAlgorithmInterface;
use SixtyEightPublishers\ArchitectureBundle\Command\CommandHandlerInterface;
use SixtyEightPublishers\UserBundle\Domain\Repository\UserRepositoryInterface;
use SixtyEightPublishers\ArchitectureBundle\Domain\Guard\CommandConsistencyGuardInterface;

final class CreateUserCommandHandler implements CommandHandlerInterface
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
	 * @param \SixtyEightPublishers\UserBundle\Domain\Command\CreateUserCommand $command
	 *
	 * @return void
	 * @throws \Exception
	 */
	public function __invoke(CreateUserCommand $command): void
	{
		($this->commandConsistencyGuard)($command);

		$classname = $this->userRepository->classname();
		$user = $classname::create($command, $this->algorithm);

		$this->userRepository->save($user);
	}
}
