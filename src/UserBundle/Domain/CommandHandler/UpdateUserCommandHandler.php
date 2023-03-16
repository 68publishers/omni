<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\Domain\CommandHandler;

use SixtyEightPublishers\ArchitectureBundle\Command\CommandHandlerInterface;
use SixtyEightPublishers\UserBundle\Domain\AttributesGuardInterface;
use SixtyEightPublishers\UserBundle\Domain\Command\UpdateUserCommand;
use SixtyEightPublishers\UserBundle\Domain\EmailAddressGuardInterface;
use SixtyEightPublishers\UserBundle\Domain\PasswordGuardInterface;
use SixtyEightPublishers\UserBundle\Domain\PasswordHashAlgorithmInterface;
use SixtyEightPublishers\UserBundle\Domain\UsernameGuardInterface;
use SixtyEightPublishers\UserBundle\Domain\UserRepositoryInterface;
use SixtyEightPublishers\UserBundle\Domain\ValueObject\UserId;

final class UpdateUserCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly PasswordHashAlgorithmInterface $algorithm,
        private readonly ?PasswordGuardInterface $passwordGuard = null,
        private readonly ?UsernameGuardInterface $usernameGuard = null,
        private readonly ?EmailAddressGuardInterface $emailAddressGuard = null,
        private readonly ?AttributesGuardInterface $attributesGuard = null,
    ) {}

    public function __invoke(UpdateUserCommand $command): void
    {
        $user = $this->userRepository->get(UserId::fromNative($command->userId));

        if (null !== $command->username) {
            $user->changeUsername($command->username, $this->usernameGuard);
        }

        if (null !== $command->password) {
            $user->changePassword($command->password, $this->algorithm, $this->passwordGuard);
        }

        if (null !== $command->emailAddress) {
            $user->changeEmailAddress($command->emailAddress, $this->emailAddressGuard);
        }

        if (null !== $command->active) {
            $user->changeActiveState($command->active);
        }

        if (null !== $command->firstname || null !== $command->surname) {
            $user->changeName($command->firstname, $command->surname);
        }

        if (null !== $command->roles) {
            $user->changeRoles($command->roles);
        }

        if (null !== $command->attributes) {
            $user->addAttributes($command->attributes, $this->attributesGuard);
        }

        $this->userRepository->save($user);
    }
}
