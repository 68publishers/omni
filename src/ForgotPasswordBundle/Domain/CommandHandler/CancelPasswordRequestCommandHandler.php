<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ForgotPasswordBundle\Domain\CommandHandler;

use SixtyEightPublishers\ArchitectureBundle\Command\CommandHandlerInterface;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\Command\CancelPasswordRequestCommand;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\PasswordRequestRepositoryInterface;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\ValueObject\PasswordRequestId;

final class CancelPasswordRequestCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly PasswordRequestRepositoryInterface $passwordRequestRepository,
    ) {}

    public function __invoke(CancelPasswordRequestCommand $command): void
    {
        $passwordRequest = $this->passwordRequestRepository->get(PasswordRequestId::fromNative($command->passwordRequestId));

        $passwordRequest->cancel($command);
        $this->passwordRequestRepository->save($passwordRequest);
    }
}
