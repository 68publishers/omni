<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\Domain\Event;

use SixtyEightPublishers\ArchitectureBundle\Domain\Event\AbstractDomainEvent;
use SixtyEightPublishers\UserBundle\Domain\ValueObject\Locale;
use SixtyEightPublishers\UserBundle\Domain\ValueObject\UserId;

final class UserLocaleChanged extends AbstractDomainEvent
{
    public static function create(UserId $userId, Locale $locale): self
    {
        return self::occur($userId->toNative(), [
            'locale' => $locale,
        ]);
    }

    public function getLocale(): Locale
    {
        return Locale::fromNative($this->parameters['locale']);
    }
}
