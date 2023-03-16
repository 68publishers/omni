<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\Domain\Event;

use SixtyEightPublishers\ArchitectureBundle\Domain\Event\AbstractDomainEvent;
use SixtyEightPublishers\UserBundle\Domain\ValueObject\Active;
use SixtyEightPublishers\UserBundle\Domain\ValueObject\UserId;

final class UserActiveStateChanged extends AbstractDomainEvent
{
    public static function create(UserId $userId, Active $active): self
    {
        return self::occur($userId->toNative(), [
            'active' => $active,
        ]);
    }

    public function getActive(): Active
    {
        return Active::fromNative($this->parameters['active']);
    }
}
