<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\Domain;

use SixtyEightPublishers\UserBundle\Domain\Exception\PasswordException;
use function error_get_last;
use function password_hash;

final class PasswordHashAlgorithm implements PasswordHashAlgorithmInterface
{
    /**
     * @param array<string, mixed> $options
     */
    public function __construct(
        private readonly string|int $algo = PASSWORD_DEFAULT,
        private readonly array $options = [],
    ) {}

    public function hash(string $rawPassword): string
    {
        if ('' === $rawPassword) {
            throw PasswordException::emptyPassword();
        }

        $hash = @password_hash($rawPassword, $this->algo, $this->options);

        if (!$hash) {
            throw PasswordException::unableToHashPassword(error_get_last()['message'] ?? 'Unknown error.');
        }

        return $hash;
    }
}
