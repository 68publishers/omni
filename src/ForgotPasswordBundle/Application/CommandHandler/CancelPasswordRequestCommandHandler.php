<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ForgotPasswordBundle\Application\CommandHandler;

use SixtyEightPublishers\ForgotPasswordBundle\Domain\Dto\PasswordRequestId;
use SixtyEightPublishers\ArchitectureBundle\Command\CommandHandlerInterface;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\Command\CancelPasswordRequestCommand;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\Repository\PasswordRequestRepositoryInterface;

final class CancelPasswordRequestCommandHandler implements CommandHandlerInterface
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
	 * @param \SixtyEightPublishers\ForgotPasswordBundle\Domain\Command\CancelPasswordRequestCommand $command
	 *
	 * @return void
	 */
	public function __invoke(CancelPasswordRequestCommand $command): void
	{
		$passwordRequest = $this->passwordRequestRepository->get(PasswordRequestId::fromString($command->passwordRequestId()));

		$passwordRequest->cancel($command);

		$this->passwordRequestRepository->save($passwordRequest);
	}
}
