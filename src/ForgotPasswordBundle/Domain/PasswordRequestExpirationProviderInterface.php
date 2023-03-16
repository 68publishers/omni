<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ForgotPasswordBundle\Domain;

use DateTimeImmutable;

interface PasswordRequestExpirationProviderInterface
{
    public function provideExpiration(DateTimeImmutable $requestedAt): DateTimeImmutable;
}
