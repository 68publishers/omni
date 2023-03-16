<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\Domain\CommandHandler;

use SixtyEightPublishers\ArchitectureBundle\Command\CommandHandlerInterface;
use SixtyEightPublishers\UserBundle\Domain\AttributesGuardInterface;
use SixtyEightPublishers\UserBundle\Domain\Command\CreateUserCommand;
use SixtyEightPublishers\UserBundle\Domain\EmailAddressGuardInterface;
use SixtyEightPublishers\UserBundle\Domain\PasswordGuardInterface;
use SixtyEightPublishers\UserBundle\Domain\PasswordHashAlgorithmInterface;
use SixtyEightPublishers\UserBundle\Domain\User;
use SixtyEightPublishers\UserBundle\Domain\UsernameGuardInterface;
use SixtyEightPublishers\UserBundle\Domain\UserRepositoryInterface;

final class CreateUserCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly PasswordHashAlgorithmInterface $algorithm,
        private readonly ?PasswordGuardInterface $passwordGuard = null,
        private readonly ?UsernameGuardInterface $usernameGuard = null,
        private readonly ?EmailAddressGuardInterface $emailAddressGuard = null,
        private readonly ?AttributesGuardInterface $attributesGuard = null,
    ) {}

    public function __invoke(CreateUserCommand $command): void
    {
        $user = User::create($command, $this->algorithm, $this->passwordGuard, $this->usernameGuard, $this->emailAddressGuard, $this->attributesGuard);

        $this->userRepository->save($user);
    }
}
