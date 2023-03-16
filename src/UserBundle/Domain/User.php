<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\Domain;

use DateTimeImmutable;
use SixtyEightPublishers\ArchitectureBundle\Domain\AggregateRootInterface;
use SixtyEightPublishers\ArchitectureBundle\Domain\DeletableAggregateRootTrait;
use SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\AggregateId;
use SixtyEightPublishers\UserBundle\Domain\Command\CreateUserCommand;
use SixtyEightPublishers\UserBundle\Domain\Event\UserActiveStateChanged;
use SixtyEightPublishers\UserBundle\Domain\Event\UserAttributesAdded;
use SixtyEightPublishers\UserBundle\Domain\Event\UserCreated;
use SixtyEightPublishers\UserBundle\Domain\Event\UserEmailAddressChanged;
use SixtyEightPublishers\UserBundle\Domain\Event\UserNameChanged;
use SixtyEightPublishers\UserBundle\Domain\Event\UserPasswordChanged;
use SixtyEightPublishers\UserBundle\Domain\Event\UserRolesChanged;
use SixtyEightPublishers\UserBundle\Domain\Event\UserUsernameChanged;
use SixtyEightPublishers\UserBundle\Domain\ValueObject\Active;
use SixtyEightPublishers\UserBundle\Domain\ValueObject\Attributes;
use SixtyEightPublishers\UserBundle\Domain\ValueObject\EmailAddress;
use SixtyEightPublishers\UserBundle\Domain\ValueObject\Firstname;
use SixtyEightPublishers\UserBundle\Domain\ValueObject\HashedPassword;
use SixtyEightPublishers\UserBundle\Domain\ValueObject\Name;
use SixtyEightPublishers\UserBundle\Domain\ValueObject\Password;
use SixtyEightPublishers\UserBundle\Domain\ValueObject\Role;
use SixtyEightPublishers\UserBundle\Domain\ValueObject\Roles;
use SixtyEightPublishers\UserBundle\Domain\ValueObject\Surname;
use SixtyEightPublishers\UserBundle\Domain\ValueObject\UserId;
use SixtyEightPublishers\UserBundle\Domain\ValueObject\Username;

class User implements AggregateRootInterface
{
    use DeletableAggregateRootTrait;

    protected UserId $id;

    protected DateTimeImmutable $createdAt;

    protected Username $username;

    protected HashedPassword $password;

    protected EmailAddress $emailAddress;

    protected Active $active;

    protected Name $name;

    protected Roles $roles;

    protected Attributes $attributes;

    public static function create(
        CreateUserCommand $command,
        PasswordHashAlgorithmInterface $algorithm,
        ?PasswordGuardInterface $passwordGuard = null,
        ?UsernameGuardInterface $usernameGuard = null,
        ?EmailAddressGuardInterface $emailAddressGuard = null,
        AttributesGuardInterface $attributesGuard = null,
    ): static {
        $user = new static(); // @phpstan-ignore-line

        $userId = null !== $command->userId ? UserId::fromNative($command->userId) : UserId::new();
        $password = Password::fromNative($command->password);
        $username = Username::fromNative($command->username);
        $emailAddress = EmailAddress::fromNative($command->emailAddress);
        $active = Active::fromNative($command->active);
        $name = new Name(Firstname::fromNative($command->firstname), Surname::fromNative($command->surname));
        $roles = Roles::fromNative($command->roles);
        $attributes = Attributes::fromNative($command->attributes);

        $passwordGuard && $passwordGuard($userId, $password);
        $usernameGuard && $usernameGuard($userId, $username);
        $emailAddressGuard && $emailAddressGuard($userId, $emailAddress);
        $attributesGuard && $attributesGuard($userId, $attributes);

        $user->recordThat(UserCreated::create(
            $userId,
            $username,
            $password->createHashedPassword($algorithm),
            $emailAddress,
            $active,
            $name,
            $roles,
            $attributes,
        ));

        return $user;
    }

    public function getAggregateId(): AggregateId
    {
        return AggregateId::fromUuid($this->id->toUuid());
    }

    public function changeUsername(string $username, ?UsernameGuardInterface $usernameGuard = null): void
    {
        $username = Username::fromNative($username);

        if (!$this->username->equals($username)) {
            $usernameGuard && $usernameGuard($this->id, $username);
            $this->recordThat(UserUsernameChanged::create($this->id, $username));
        }
    }

    public function changePassword(string $password, PasswordHashAlgorithmInterface $algorithm, ?PasswordGuardInterface $passwordGuard = null): void
    {
        $password = Password::fromNative($password);

        $passwordGuard && $passwordGuard($this->id, $password);
        $this->recordThat(UserPasswordChanged::create($this->id, $password->createHashedPassword($algorithm)));
    }

    public function changeEmailAddress(string $emailAddress, ?EmailAddressGuardInterface $emailAddressGuard = null): void
    {
        $emailAddress = EmailAddress::fromNative($emailAddress);

        if (!$this->emailAddress->equals($emailAddress)) {
            $emailAddressGuard && $emailAddressGuard($this->id, $emailAddress);
            $this->recordThat(UserEmailAddressChanged::create($this->id, $emailAddress));
        }
    }

    public function changeActiveState(bool $active): void
    {
        $active = Active::fromNative($active);

        if (!$this->active->equals($active)) {
            $this->recordThat(UserActiveStateChanged::create($this->id, $active));
        }
    }

    public function changeName(?string $firstname, ?string $surname): void
    {
        $name = new Name(
            $firstname ? Firstname::fromNative($firstname) : $this->name->getFirstname(),
            $surname ? Surname::fromNative($surname) : $this->name->getSurname(),
        );

        if (!$this->name->equals($name)) {
            $this->recordThat(UserNameChanged::create($this->id, $name));
        }
    }

    public function addRole(string $role): void
    {
        $role = Role::fromNative($role);

        if (!$this->roles->has($role)) {
            $this->recordThat(UserRolesChanged::create($this->id, $this->roles->with($role)));
        }
    }

    public function removeRole(string $role): void
    {
        $role = Role::fromNative($role);

        if ($this->roles->has($role)) {
            $this->recordThat(UserRolesChanged::create($this->id, $this->roles->without($role)));
        }
    }

    /**
     * @param array<string> $roles
     */
    public function changeRoles(array $roles): void
    {
        $roles = Roles::fromNative($roles);

        if (!$this->roles->equals($roles)) {
            $this->recordThat(UserRolesChanged::create($this->id, $roles));
        }
    }

    /**
     * @param array<string, mixed> $attributes
     */
    public function addAttributes(array $attributes, ?AttributesGuardInterface $attributesGuard = null): void
    {
        $attributes = Attributes::fromNative($attributes);
        $mergedAttributes = $this->attributes->merge($attributes);

        if (!$this->attributes->equals($mergedAttributes)) {
            $attributesGuard && $attributesGuard($this->id, $mergedAttributes);
            $this->recordThat(UserAttributesAdded::create($this->id, $attributes));
        }
    }

    protected function whenUserCreated(UserCreated $event): void
    {
        $this->id = UserId::fromUuid($event->getAggregateId()->toUuid());
        $this->createdAt = $event->getCreatedAt();
        $this->username = $event->getUsername();
        $this->password = $event->getPassword();
        $this->emailAddress = $event->getEmailAddress();
        $this->active = $event->getActive();
        $this->name = $event->getName();
        $this->roles = $event->getRoles();
        $this->attributes = $event->getAttributes();
    }

    protected function whenUserUsernameChanged(UserUsernameChanged $event): void
    {
        $this->username = $event->getUsername();
    }

    protected function whenUserPasswordChanged(UserPasswordChanged $event): void
    {
        $this->password = $event->getPassword();
    }

    protected function whenUserEmailAddressChanged(UserEmailAddressChanged $event): void
    {
        $this->emailAddress = $event->getEmailAddress();
    }

    protected function whenUserActiveStateChanged(UserActiveStateChanged $event): void
    {
        $this->active = $event->getActive();
    }

    protected function whenUserNameChanged(UserNameChanged $event): void
    {
        $this->name = $event->getName();
    }

    protected function whenUserRolesChanged(UserRolesChanged $event): void
    {
        $this->roles = $event->getRoles();
    }

    protected function whenUserAttributesAdded(UserAttributesAdded $event): void
    {
        $this->attributes = $this->attributes->merge($event->getAttributes());
    }
}
