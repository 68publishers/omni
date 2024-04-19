<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\Domain\Event;

use DateTimeZone;
use SixtyEightPublishers\ArchitectureBundle\Domain\Event\AbstractDomainEvent;
use SixtyEightPublishers\UserBundle\Domain\ValueObject\UserId;

final class UserTimezoneChanged extends AbstractDomainEvent
{
    public static function create(UserId $userId, DateTimeZone $timezone): self
    {
        return self::occur($userId, [
            'timezone' => $timezone,
        ]);
    }

    public function getAggregateId(): UserId
    {
        return UserId::fromSafeNative($this->getNativeAggregatedId());
    }

    public function getTimezone(): DateTimeZone
    {
        return new DateTimeZone($this->parameters['timezone']);
    }
}
