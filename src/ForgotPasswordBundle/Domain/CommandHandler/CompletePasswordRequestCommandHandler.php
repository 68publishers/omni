<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ForgotPasswordBundle\Domain\CommandHandler;

use SixtyEightPublishers\ArchitectureBundle\Command\CommandHandlerInterface;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\Command\CompletePasswordRequestCommand;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\PasswordRequestRepositoryInterface;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\ValueObject\PasswordRequestId;

final class CompletePasswordRequestCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly PasswordRequestRepositoryInterface $passwordRequestRepository,
    ) {}

    public function __invoke(CompletePasswordRequestCommand $command): void
    {
        $passwordRequest = $this->passwordRequestRepository->get(PasswordRequestId::fromNative($command->passwordRequestId));

        $passwordRequest->complete($command);
        $this->passwordRequestRepository->save($passwordRequest);
    }
}
