<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ForgotPasswordBundle\Domain\CommandHandler;

use Exception;
use SixtyEightPublishers\ArchitectureBundle\Command\CommandHandlerInterface;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\Command\RequestPasswordChangeCommand;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\EmailAddressGuardInterface;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\PasswordRequest;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\PasswordRequestExpirationProviderInterface;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\PasswordRequestRepositoryInterface;

final class RequestPasswordChangeCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly PasswordRequestRepositoryInterface $passwordRequestRepository,
        private readonly PasswordRequestExpirationProviderInterface $passwordRequestExpirationProvider,
        private readonly ?EmailAddressGuardInterface $emailAddressGuard = null,
    ) {}

    /**
     * @throws Exception
     */
    public function __invoke(RequestPasswordChangeCommand $command): void
    {
        $passwordRequest = PasswordRequest::requestPasswordChange($command, $this->passwordRequestExpirationProvider, $this->emailAddressGuard);

        $this->passwordRequestRepository->save($passwordRequest);
    }
}
