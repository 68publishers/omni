<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\Domain\Event;

use SixtyEightPublishers\ArchitectureBundle\Domain\Event\AbstractDomainEvent;
use SixtyEightPublishers\UserBundle\Domain\ValueObject\UserId;
use SixtyEightPublishers\UserBundle\Domain\ValueObject\Username;

final class UserUsernameChanged extends AbstractDomainEvent
{
    public static function create(UserId $userId, Username $username): self
    {
        return self::occur($userId, [
            'username' => $username,
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
}
