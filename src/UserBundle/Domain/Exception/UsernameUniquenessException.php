<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\Domain\Exception;

use DomainException;
use function sprintf;

final class UsernameUniquenessException extends DomainException
{
    public function __construct(
        string $message,
        public readonly string $username,
    ) {
        parent::__construct($message);
    }

    public static function create(string $username): self
    {
        return new self(sprintf(
            'User with the username "%s" already exists.',
            $username,
        ), $username);
    }
}
