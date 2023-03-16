<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\Domain\CommandHandler;

use SixtyEightPublishers\ArchitectureBundle\Command\CommandHandlerInterface;
use SixtyEightPublishers\UserBundle\Domain\Command\DeleteUserCommand;
use SixtyEightPublishers\UserBundle\Domain\UserRepositoryInterface;
use SixtyEightPublishers\UserBundle\Domain\ValueObject\UserId;

final class DeleteUserCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
    ) {}

    public function __invoke(DeleteUserCommand $command): void
    {
        $user = $this->userRepository->get(UserId::fromNative($command->userId));
        $user->delete();

        $this->userRepository->save($user);
    }
}
