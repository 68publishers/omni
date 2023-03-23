<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\Domain;

use SixtyEightPublishers\UserBundle\Domain\ValueObject\Locale;
use SixtyEightPublishers\UserBundle\Domain\ValueObject\UserId;

interface LocaleGuardInterface
{
    public function __invoke(UserId $userId, Locale $locale): void;
}
