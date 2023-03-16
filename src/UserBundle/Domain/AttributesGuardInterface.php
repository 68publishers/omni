<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\Domain;

use SixtyEightPublishers\UserBundle\Domain\ValueObject\Attributes;
use SixtyEightPublishers\UserBundle\Domain\ValueObject\UserId;

interface AttributesGuardInterface
{
    public function __invoke(UserId $userId, Attributes $attributes): void;
}
