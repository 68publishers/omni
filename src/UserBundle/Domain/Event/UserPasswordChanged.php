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
        return self::occur($userId, [
            'password' => $password,
        ]);
    }

    public function getAggregateId(): UserId
    {
        return UserId::fromSafeNative($this->getNativeAggregatedId());
    }

    public function getPassword(): HashedPassword
    {
        return HashedPassword::fromNative($this->parameters['password']);
    }
}
