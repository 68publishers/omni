<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\Domain\Event;

use DateTimeZone;
use SixtyEightPublishers\ArchitectureBundle\Domain\Event\AbstractDomainEvent;
use SixtyEightPublishers\UserBundle\Domain\ValueObject\Active;
use SixtyEightPublishers\UserBundle\Domain\ValueObject\Attributes;
use SixtyEightPublishers\UserBundle\Domain\ValueObject\EmailAddress;
use SixtyEightPublishers\UserBundle\Domain\ValueObject\HashedPassword;
use SixtyEightPublishers\UserBundle\Domain\ValueObject\Locale;
use SixtyEightPublishers\UserBundle\Domain\ValueObject\Name;
use SixtyEightPublishers\UserBundle\Domain\ValueObject\Roles;
use SixtyEightPublishers\UserBundle\Domain\ValueObject\UserId;
use SixtyEightPublishers\UserBundle\Domain\ValueObject\Username;

final class UserCreated extends AbstractDomainEvent
{
    public static function create(
        UserId $userId,
        Username $username,
        ?HashedPassword $password,
        EmailAddress $emailAddress,
        Active $active,
        Name $name,
        Roles $roles,
        Locale $locale,
        DateTimeZone $timezone,
        Attributes $attributes,
    ): self {
        return self::occur($userId, [
            'username' => $username,
            'password' => $password,
            'email_address' => $emailAddress,
            'active' => $active,
            'name' => $name,
            'roles' => $roles,
            'locale' => $locale,
            'timezone' => $timezone,
            'attributes' => $attributes,
        ]);
    }

    public function getAggregateId(): UserId
    {
        return UserId::fromSafeNative($this->getNativeAggregatedId());
    }

    public function getUsername(): Username
    {
        return Username::fromNative($this->parameters['username']);
    }

    public function getPassword(): ?HashedPassword
    {
        return null !== $this->parameters['password'] ? HashedPassword::fromNative($this->parameters['password']) : null;
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

    public function getLocale(): Locale
    {
        return Locale::fromNative($this->parameters['locale']);
    }

    public function getTimezone(): DateTimeZone
    {
        return new DateTimeZone($this->parameters['timezone']);
    }

    public function getAttributes(): Attributes
    {
        return Attributes::fromNative($this->parameters['attributes']);
    }
}
