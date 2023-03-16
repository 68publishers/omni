<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ForgotPasswordBundle\Domain\Exception;

use DomainException;

final class PasswordRequestExpiredException extends DomainException
{
    public static function create(): self
    {
        return new self('Password change request is expired.');
    }
}
