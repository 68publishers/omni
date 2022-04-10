<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ForgotPasswordBundle\Application\CommandHandler;

use SixtyEightPublishers\ForgotPasswordBundle\Domain\Dto\PasswordRequestId;
use SixtyEightPublishers\ArchitectureBundle\Command\CommandHandlerInterface;
use SixtyEightPublishers\ArchitectureBundle\Domain\Guard\CommandConsistencyGuardInterface;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\Command\CancelPasswordRequestCommand;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\Repository\PasswordRequestRepositoryInterface;

final class CancelPasswordRequestCommandHandler implements CommandHandlerInterface
{
	private PasswordRequestRepositoryInterface $passwordRequestRepository;

	private CommandConsistencyGuardInterface $commandConsistencyGuard;

	/**
	 * @param \SixtyEightPublishers\ForgotPasswordBundle\Domain\Repository\PasswordRequestRepositoryInterface $passwordRequestRepository
	 * @param \SixtyEightPublishers\ArchitectureBundle\Domain\Guard\CommandConsistencyGuardInterface          $commandConsistencyGuard
	 */
	public function __construct(PasswordRequestRepositoryInterface $passwordRequestRepository, CommandConsistencyGuardInterface $commandConsistencyGuard)
	{
		$this->passwordRequestRepository = $passwordRequestRepository;
		$this->commandConsistencyGuard = $commandConsistencyGuard;
	}

	/**
	 * @param \SixtyEightPublishers\ForgotPasswordBundle\Domain\Command\CancelPasswordRequestCommand $command
	 *
	 * @return void
	 */
	public function __invoke(CancelPasswordRequestCommand $command): void
	{
		($this->commandConsistencyGuard)($command);

		$passwordRequest = $this->passwordRequestRepository->get(PasswordRequestId::fromString($command->passwordRequestId()));

		$passwordRequest->cancel($command);

		$this->passwordRequestRepository->save($passwordRequest);
	}
}
