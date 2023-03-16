<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\Domain\Exception;

use DomainException;
use function implode;
use function sprintf;

final class PasswordException extends DomainException
{
    public const REASON_EMPTY_PASSWORD = 'empty_password';
    public const REASON_UNABLE_TO_HASH_PASSWORD = 'unable_to_hash_password';
    public const REASON_PASSWORD_DOES_NOT_MEET_CONDITIONS = 'password_does_not_meet_conditions';

    public function __construct(
        string $message,
        public readonly string $reason,
    ) {
        parent::__construct($message);
    }

    public static function emptyPassword(): self
    {
        return new self('Password can\'t be empty.', self::REASON_EMPTY_PASSWORD);
    }

    public static function unableToHashPassword(string $reason): self
    {
        return new self(sprintf(
            'Can\'t hash a password. %s',
            $reason,
        ), self::REASON_UNABLE_TO_HASH_PASSWORD);
    }

    /**
     * @param array<string> $conditions
     */
    public static function passwordDoesNotMeetConditions(array $conditions): self
    {
        return new self(sprintf(
            'Password must meet the following conditions: "%s".',
            implode('", "', $conditions),
        ), self::REASON_PASSWORD_DOES_NOT_MEET_CONDITIONS);
    }
}
