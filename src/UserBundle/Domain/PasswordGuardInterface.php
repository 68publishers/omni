<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\Domain;

use SixtyEightPublishers\UserBundle\Domain\ValueObject\Password;
use SixtyEightPublishers\UserBundle\Domain\ValueObject\UserId;

interface PasswordGuardInterface
{
    public function __invoke(UserId $userId, Password $password): void;
}
