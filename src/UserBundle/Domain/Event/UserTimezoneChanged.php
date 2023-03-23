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
        return self::occur($userId->toNative(), [
            'timezone' => $timezone,
        ]);
    }

    public function getTimezone(): DateTimeZone
    {
        return new DateTimeZone($this->parameters['timezone']);
    }
}
