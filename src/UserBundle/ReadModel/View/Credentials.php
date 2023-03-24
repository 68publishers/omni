<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\ReadModel\View;

use SixtyEightPublishers\UserBundle\Domain\ValueObject\HashedPassword;
use SixtyEightPublishers\UserBundle\Domain\ValueObject\UserId;
use SixtyEightPublishers\UserBundle\Domain\ValueObject\Username;

class Credentials
{
    public function __construct(
        public readonly UserId $userId,
        public readonly Username $username,
        public readonly ?HashedPassword $password,
    ) {}
}
