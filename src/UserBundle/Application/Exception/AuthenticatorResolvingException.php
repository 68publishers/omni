<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\Application\Exception;

use Exception;
use function sprintf;

final class AuthenticatorResolvingException extends Exception
{
    public static function missingDefault(): self
    {
        return new self('Can\'t authenticate a user because the default authenticator is missing.');
    }

    public static function missingAuthenticator(string $name): self
    {
        return new self(sprintf(
            'Can\'t authenticate a user because an authenticator with the name %s is missing.',
            $name,
        ));
    }
}
