<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ForgotPasswordBundle\Domain\Exception;

use DomainException;

final class PasswordRequestExpiredException extends DomainException
{
	/**
	 * @param string $message
	 */
	private function __construct(string $message)
	{
		parent::__construct($message);
	}

	/**
	 * @return static
	 */
	public static function create(): self
	{
		return new self('Password change request is expired.');
	}
}
