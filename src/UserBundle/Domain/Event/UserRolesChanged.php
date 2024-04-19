<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\Domain\Event;

use SixtyEightPublishers\ArchitectureBundle\Domain\Event\AbstractDomainEvent;
use SixtyEightPublishers\UserBundle\Domain\ValueObject\Roles;
use SixtyEightPublishers\UserBundle\Domain\ValueObject\UserId;

final class UserRolesChanged extends AbstractDomainEvent
{
    public static function create(UserId $userId, Roles $roles): self
    {
        return self::occur($userId, [
            'roles' => $roles,
        ]);
    }

    public function getAggregateId(): UserId
    {
        return UserId::fromSafeNative($this->getNativeAggregatedId());
    }

    public function getRoles(): Roles
    {
        return Roles::fromNative($this->parameters['roles']);
    }
}
