<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\Domain\Exception;

use DomainException;
use SixtyEightPublishers\UserBundle\Domain\ValueObject\UserId;
use function sprintf;

final class UserNotFoundException extends DomainException
{
    public static function withId(UserId $id): self
    {
        return new self(sprintf(
            'User with the ID %s not found.',
            $id->toNative(),
        ));
    }
}
