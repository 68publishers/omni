<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\Domain\Event;

use SixtyEightPublishers\ArchitectureBundle\Domain\Event\AbstractDomainEvent;
use SixtyEightPublishers\UserBundle\Domain\ValueObject\Active;
use SixtyEightPublishers\UserBundle\Domain\ValueObject\Attributes;
use SixtyEightPublishers\UserBundle\Domain\ValueObject\EmailAddress;
use SixtyEightPublishers\UserBundle\Domain\ValueObject\HashedPassword;
use SixtyEightPublishers\UserBundle\Domain\ValueObject\Name;
use SixtyEightPublishers\UserBundle\Domain\ValueObject\Roles;
use SixtyEightPublishers\UserBundle\Domain\ValueObject\UserId;
use SixtyEightPublishers\UserBundle\Domain\ValueObject\Username;

final class UserCreated extends AbstractDomainEvent
{
    public static function create(
        UserId $userId,
        Username $username,
        HashedPassword $password,
        EmailAddress $emailAddress,
        Active $active,
        Name $name,
        Roles $roles,
        Attributes $attributes,
    ): self {
        return self::occur($userId->toNative(), [
            'username' => $username,
            'password' => $password,
            'email_address' => $emailAddress,
            'active' => $active,
            'name' => $name,
            'roles' => $roles,
            'attributes' => $attributes,
        ]);
    }

    public function getUsername(): Username
    {
        return Username::fromNative($this->parameters['username']);
    }

    public function getPassword(): HashedPassword
    {
        return HashedPassword::fromNative($this->parameters['password']);
    }

    public function getEmailAddress(): EmailAddress
    {
        return EmailAddress::fromNative($this->parameters['email_address']);
    }

    public function getActive(): Active
    {
        return Active::fromNative($this->parameters['active']);
    }

    public function getName(): Name
    {
        return Name::fromNative($this->parameters['name']);
    }

    public function getRoles(): Roles
    {
        return Roles::fromNative($this->parameters['roles']);
    }

    public function getAttributes(): Attributes
    {
        return Attributes::fromNative($this->parameters['attributes']);
    }
}
