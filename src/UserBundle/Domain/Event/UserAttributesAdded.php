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
        return self::occur($userId, [
            'attributes' => $attributes,
        ]);
    }

    public function getAggregateId(): UserId
    {
        return UserId::fromSafeNative($this->getNativeAggregatedId());
    }

    public function getAttributes(): Attributes
    {
        return Attributes::fromNative($this->parameters['attributes']);
    }
}
