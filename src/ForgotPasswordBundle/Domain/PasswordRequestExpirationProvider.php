<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ForgotPasswordBundle\Domain;

use DateTimeImmutable;

final class PasswordRequestExpirationProvider implements PasswordRequestExpirationProviderInterface
{
	private string $dateTimeModifier;

	/**
	 * @param string $dateTimeModifier
	 */
	public function __construct(string $dateTimeModifier = '+1 hour')
	{
		$this->dateTimeModifier = $dateTimeModifier;
	}

	/**
	 * {@inheritDoc}
	 */
	public function provideExpiration(DateTimeImmutable $requestedAt): DateTimeImmutable
	{
		return $requestedAt->modify($this->dateTimeModifier);
	}
}
