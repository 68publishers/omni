<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ForgotPasswordBundle\Domain;

use DateTimeImmutable;

final class PasswordRequestExpirationProvider implements PasswordRequestExpirationProviderInterface
{
    public function __construct(
        private readonly string $dateTimeModifier = '+1 hour',
    ) {}

    public function provideExpiration(DateTimeImmutable $requestedAt): DateTimeImmutable
    {
        return $requestedAt->modify($this->dateTimeModifier);
    }
}
