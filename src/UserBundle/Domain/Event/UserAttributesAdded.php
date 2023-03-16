<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\Domain\Event;

use SixtyEightPublishers\ArchitectureBundle\Domain\Event\AbstractDomainEvent;
use SixtyEightPublishers\UserBundle\Domain\ValueObject\Attributes;
use SixtyEightPublishers\UserBundle\Domain\ValueObject\UserId;

final class UserAttributesAdded extends AbstractDomainEvent
{
    public static function create(UserId $userId, Attributes $attributes): self
    {
        return self::occur($userId->toNative(), [
            'attributes' => $attributes,
        ]);
    }

    public function getAttributes(): Attributes
    {
        return Attributes::fromNative($this->parameters['attributes']);
    }
}
