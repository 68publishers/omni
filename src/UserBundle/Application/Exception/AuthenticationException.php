<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\Application\Exception;

use Exception;
use function sprintf;

final class AuthenticationException extends Exception
{
    public const REASON_USER_NOT_FOUND = 'user_not_found';
    public const REASON_INVALID_PASSWORD = 'invalid_password';
    public const REASON_IDENTITY_ERROR = 'identity_error';

    private function __construct(
        string $message,
        public readonly string $reason,
    ) {
        parent::__construct($message);
    }

    public static function userNotFound(string $username): self
    {
        return new self(sprintf(
            'User with the username %s not found.',
            $username,
        ), self::REASON_USER_NOT_FOUND);
    }

    public static function invalidPassword(string $username): self
    {
        return new self(sprintf(
            'Invalid password for the user with the username %s.',
            $username,
        ), self::REASON_INVALID_PASSWORD);
    }

    public static function fromIdentityException(IdentityException $exception): self
    {
        return new self(sprintf(
            'Identity error: %s',
            $exception->getMessage(),
        ), self::REASON_IDENTITY_ERROR);
    }
}
