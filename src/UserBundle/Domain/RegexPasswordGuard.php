<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\Domain;

use SixtyEightPublishers\UserBundle\Domain\Exception\PasswordException;
use SixtyEightPublishers\UserBundle\Domain\ValueObject\Password;
use SixtyEightPublishers\UserBundle\Domain\ValueObject\UserId;
use function preg_match;

final class RegexPasswordGuard implements PasswordGuardInterface
{
    public function __construct(
        private readonly string $regex,
    ) {}

    public function __invoke(UserId $userId, Password $password): void
    {
        if ($password->isNull()) {
            return;
        }

        $passwordString = (string) $password->toNative();

        if (!preg_match('/' . $this->regex . '/', $passwordString)) {
            throw PasswordException::passwordDoesNotMeetConditions([
                'password must match the regex /' . $this->regex . '/',
            ]);
        }
    }
}
