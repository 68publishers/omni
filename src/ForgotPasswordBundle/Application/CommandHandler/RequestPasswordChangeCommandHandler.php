<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ForgotPasswordBundle\Application\CommandHandler;

use SixtyEightPublishers\ArchitectureBundle\Command\CommandHandlerInterface;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\CheckEmailAddressExistsInterface;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\Command\RequestPasswordChangeCommand;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\PasswordRequestExpirationProviderInterface;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\Repository\PasswordRequestRepositoryInterface;

final class RequestPasswordChangeCommandHandler implements CommandHandlerInterface
{
	private PasswordRequestRepositoryInterface $passwordRequestRepository;

	private PasswordRequestExpirationProviderInterface $passwordRequestExpirationProvider;

	private CheckEmailAddressExistsInterface $checkEmailAddressExists;

	/**
	 * @param \SixtyEightPublishers\ForgotPasswordBundle\Domain\Repository\PasswordRequestRepositoryInterface $passwordRequestRepository
	 * @param \SixtyEightPublishers\ForgotPasswordBundle\Domain\PasswordRequestExpirationProviderInterface    $passwordRequestExpirationProvider
	 * @param \SixtyEightPublishers\ForgotPasswordBundle\Domain\CheckEmailAddressExistsInterface              $checkEmailAddressExists
	 */
	public function __construct(PasswordRequestRepositoryInterface $passwordRequestRepository, PasswordRequestExpirationProviderInterface $passwordRequestExpirationProvider, CheckEmailAddressExistsInterface $checkEmailAddressExists)
	{
		$this->passwordRequestRepository = $passwordRequestRepository;
		$this->passwordRequestExpirationProvider = $passwordRequestExpirationProvider;
		$this->checkEmailAddressExists = $checkEmailAddressExists;
	}

	/**
	 * @param \SixtyEightPublishers\ForgotPasswordBundle\Domain\Command\RequestPasswordChangeCommand $command
	 *
	 * @return void
	 * @throws \Exception
	 */
	public function __invoke(RequestPasswordChangeCommand $command): void
	{
		$classname = $this->passwordRequestRepository->classname();
		$passwordRequest = $classname::requestPasswordChange($command, $this->passwordRequestExpirationProvider, $this->checkEmailAddressExists);

		$this->passwordRequestRepository->save($passwordRequest);
	}
}
