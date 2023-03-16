<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ForgotPasswordBundle\Domain\Exception;

use DomainException;
use function sprintf;

final class EmailAddressNotFoundException extends DomainException
{
    public static function create(string $emailAddress): self
    {
        return new self(sprintf(
            'The user with an email address %s not found.',
            $emailAddress,
        ));
    }
}
