<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\Domain;

use SixtyEightPublishers\UserBundle\Domain\ValueObject\EmailAddress;
use SixtyEightPublishers\UserBundle\Domain\ValueObject\UserId;

interface EmailAddressGuardInterface
{
    public function __invoke(UserId $userId, EmailAddress $emailAddress): void;
}
