<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\ReadModel\View;

use DateTimeImmutable;
use SixtyEightPublishers\UserBundle\Domain\ValueObject\Active;
use SixtyEightPublishers\UserBundle\Domain\ValueObject\Attributes;
use SixtyEightPublishers\UserBundle\Domain\ValueObject\EmailAddress;
use SixtyEightPublishers\UserBundle\Domain\ValueObject\Name;
use SixtyEightPublishers\UserBundle\Domain\ValueObject\Roles;
use SixtyEightPublishers\UserBundle\Domain\ValueObject\UserId;
use SixtyEightPublishers\UserBundle\Domain\ValueObject\Username;

class IdentityData
{
    public function __construct(
        public readonly UserId $id,
        public readonly DateTimeImmutable $createdAt,
        public readonly Username $username,
        public readonly EmailAddress $emailAddress,
        public readonly Active $active,
        public readonly Name $name,
        public readonly Roles $roles,
        public readonly Attributes $attributes,
    ) {}
}
