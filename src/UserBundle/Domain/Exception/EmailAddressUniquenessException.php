<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\Domain\Exception;

use DomainException;
use function sprintf;

final class EmailAddressUniquenessException extends DomainException
{
    public function __construct(
        string $message,
        public readonly string $emailAddress,
    ) {
        parent::__construct($message);
    }

    public static function create(string $emailAddress): self
    {
        return new self(sprintf(
            'User with the email "%s" already exists.',
            $emailAddress,
        ), $emailAddress);
    }
}
