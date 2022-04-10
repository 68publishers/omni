<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ForgotPasswordBundle\Application\CommandHandler;

use SixtyEightPublishers\ArchitectureBundle\Command\CommandHandlerInterface;
use SixtyEightPublishers\ArchitectureBundle\Domain\Guard\CommandConsistencyGuardInterface;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\Command\RequestPasswordChangeCommand;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\PasswordRequestExpirationProviderInterface;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\Repository\PasswordRequestRepositoryInterface;

final class RequestPasswordChangeCommandHandler implements CommandHandlerInterface
{
	private PasswordRequestRepositoryInterface $passwordRequestRepository;

	private CommandConsistencyGuardInterface $commandConsistencyGuard;

	private PasswordRequestExpirationProviderInterface $passwordRequestExpirationProvider;

	/**
	 * @param \SixtyEightPublishers\ForgotPasswordBundle\Domain\Repository\PasswordRequestRepositoryInterface $passwordRequestRepository
	 * @param \SixtyEightPublishers\ArchitectureBundle\Domain\Guard\CommandConsistencyGuardInterface          $commandConsistencyGuard
	 * @param \SixtyEightPublishers\ForgotPasswordBundle\Domain\PasswordRequestExpirationProviderInterface    $passwordRequestExpirationProvider
	 */
	public function __construct(PasswordRequestRepositoryInterface $passwordRequestRepository, CommandConsistencyGuardInterface $commandConsistencyGuard, PasswordRequestExpirationProviderInterface $passwordRequestExpirationProvider)
	{
		$this->passwordRequestRepository = $passwordRequestRepository;
		$this->commandConsistencyGuard = $commandConsistencyGuard;
		$this->passwordRequestExpirationProvider = $passwordRequestExpirationProvider;
	}

	/**
	 * @param \SixtyEightPublishers\ForgotPasswordBundle\Domain\Command\RequestPasswordChangeCommand $command
	 *
	 * @return void
	 * @throws \Exception
	 */
	public function __invoke(RequestPasswordChangeCommand $command): void
	{
		($this->commandConsistencyGuard)($command);

		$classname = $this->passwordRequestRepository->classname();
		$passwordRequest = $classname::requestPasswordChange($command, $this->passwordRequestExpirationProvider);

		$this->passwordRequestRepository->save($passwordRequest);
	}
}
