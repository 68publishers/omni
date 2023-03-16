<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\Domain;

use SixtyEightPublishers\UserBundle\Domain\ValueObject\UserId;
use SixtyEightPublishers\UserBundle\Domain\ValueObject\Username;

interface UsernameGuardInterface
{
    public function __invoke(UserId $userId, Username $username): void;
}
