<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ForgotPasswordBundle\Application\CommandHandler;

use SixtyEightPublishers\ForgotPasswordBundle\Domain\Dto\PasswordRequestId;
use SixtyEightPublishers\ArchitectureBundle\Command\CommandHandlerInterface;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\Command\CompletePasswordRequestCommand;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\Repository\PasswordRequestRepositoryInterface;

final class CompletePasswordRequestCommandHandler implements CommandHandlerInterface
{
	private PasswordRequestRepositoryInterface $passwordRequestRepository;

	/**
	 * @param \SixtyEightPublishers\ForgotPasswordBundle\Domain\Repository\PasswordRequestRepositoryInterface $passwordRequestRepository
	 */
	public function __construct(PasswordRequestRepositoryInterface $passwordRequestRepository)
	{
		$this->passwordRequestRepository = $passwordRequestRepository;
	}

	/**
	 * @param \SixtyEightPublishers\ForgotPasswordBundle\Domain\Command\CompletePasswordRequestCommand $command
	 *
	 * @return void
	 */
	public function __invoke(CompletePasswordRequestCommand $command): void
	{
		$passwordRequest = $this->passwordRequestRepository->get(PasswordRequestId::fromString($command->passwordRequestId()));

		$passwordRequest->complete($command);

		$this->passwordRequestRepository->save($passwordRequest);
	}
}
