<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ForgotPasswordBundle\Domain;

use DateTimeImmutable;

interface PasswordRequestExpirationProviderInterface
{
	/**
	 * @param \DateTimeImmutable $requestedAt
	 *
	 * @return \DateTimeImmutable
	 */
	public function provideExpiration(DateTimeImmutable $requestedAt): DateTimeImmutable;
}
