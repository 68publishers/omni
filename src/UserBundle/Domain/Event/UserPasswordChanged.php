<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\Domain\Event;

use SixtyEightPublishers\ArchitectureBundle\Domain\Event\AbstractDomainEvent;
use SixtyEightPublishers\UserBundle\Domain\ValueObject\HashedPassword;
use SixtyEightPublishers\UserBundle\Domain\ValueObject\UserId;

final class UserPasswordChanged extends AbstractDomainEvent
{
    public static function create(UserId $userId, HashedPassword $password): self
    {
        return self::occur($userId->toNative(), [
            'password' => $password,
        ]);
    }

    public function getPassword(): HashedPassword
    {
        return HashedPassword::fromNative($this->parameters['password']);
    }
}
