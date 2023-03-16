<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\Domain\Event;

use SixtyEightPublishers\ArchitectureBundle\Domain\Event\AbstractDomainEvent;
use SixtyEightPublishers\UserBundle\Domain\ValueObject\Name;
use SixtyEightPublishers\UserBundle\Domain\ValueObject\UserId;

final class UserNameChanged extends AbstractDomainEvent
{
    public static function create(UserId $userId, Name $name): self
    {
        return self::occur($userId->toNative(), [
            'name' => $name,
        ]);
    }

    public function getName(): Name
    {
        return Name::fromNative($this->parameters['name']);
    }
}
