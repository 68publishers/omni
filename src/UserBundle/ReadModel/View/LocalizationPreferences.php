<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\ReadModel\View;

use DateTimeZone;
use SixtyEightPublishers\UserBundle\Domain\ValueObject\Locale;
use SixtyEightPublishers\UserBundle\Domain\ValueObject\UserId;

final class LocalizationPreferences
{
    public function __construct(
        public readonly UserId $userId,
        public readonly Locale $locale,
        public readonly DateTimeZone $timezone,
    ) {}
}
