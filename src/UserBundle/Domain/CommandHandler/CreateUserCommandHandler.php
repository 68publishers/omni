<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\Domain\CommandHandler;

use SixtyEightPublishers\UserBundle\Domain\Command\CreateUserCommand;
use SixtyEightPublishers\UserBundle\Domain\PasswordHashAlgorithmInterface;
use SixtyEightPublishers\ArchitectureBundle\Command\CommandHandlerInterface;
use SixtyEightPublishers\UserBundle\Domain\CheckUsernameUniquenessInterface;
use SixtyEightPublishers\UserBundle\Domain\Repository\UserRepositoryInterface;
use SixtyEightPublishers\UserBundle\Domain\CheckEmailAddressUniquenessInterface;

final class CreateUserCommandHandler implements CommandHandlerInterface
{
	private UserRepositoryInterface $userRepository;

	private PasswordHashAlgorithmInterface $algorithm;

	private CheckEmailAddressUniquenessInterface $checkEmailAddressUniqueness;

	private CheckUsernameUniquenessInterface $checkUsernameUniqueness;

	/**
	 * @param \SixtyEightPublishers\UserBundle\Domain\Repository\UserRepositoryInterface   $userRepository
	 * @param \SixtyEightPublishers\UserBundle\Domain\PasswordHashAlgorithmInterface       $algorithm
	 * @param \SixtyEightPublishers\UserBundle\Domain\CheckEmailAddressUniquenessInterface $checkEmailAddressUniqueness
	 * @param \SixtyEightPublishers\UserBundle\Domain\CheckUsernameUniquenessInterface     $checkUsernameUniqueness
	 */
	public function __construct(UserRepositoryInterface $userRepository, PasswordHashAlgorithmInterface $algorithm, CheckEmailAddressUniquenessInterface $checkEmailAddressUniqueness, CheckUsernameUniquenessInterface $checkUsernameUniqueness)
	{
		$this->userRepository = $userRepository;
		$this->algorithm = $algorithm;
		$this->checkEmailAddressUniqueness = $checkEmailAddressUniqueness;
		$this->checkUsernameUniqueness = $checkUsernameUniqueness;
	}

	/**
	 * @param \SixtyEightPublishers\UserBundle\Domain\Command\CreateUserCommand $command
	 *
	 * @return void
	 */
	public function __invoke(CreateUserCommand $command): void
	{
		$classname = $this->userRepository->classname();
		$user = $classname::create($command, $this->algorithm, $this->checkEmailAddressUniqueness, $this->checkUsernameUniqueness);

		$this->userRepository->save($user);
	}
}
