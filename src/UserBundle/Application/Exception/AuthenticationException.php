<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\Application\Exception;

use Exception;

final class AuthenticationException extends Exception
{
	public const USER_NOT_FOUND = 1;
	public const INVALID_PASSWORD = 2;
	public const IDENTITY_ERROR = 3;

	private function __construct(string $message, int $code)
	{
		parent::__construct($message, $code);
	}

	/**
	 * @param string $username
	 *
	 * @return static
	 */
	public static function userNotFound(string $username): self
	{
		return new self(sprintf(
			'User with an username %s not found.',
			$username
		), self::USER_NOT_FOUND);
	}

	/**
	 * @param string $username
	 *
	 * @return static
	 */
	public static function invalidPassword(string $username): self
	{
		return new self(sprintf(
			'Invalid password for the user with an username %s.',
			$username
		), self::INVALID_PASSWORD);
	}

	/**
	 * @param \SixtyEightPublishers\UserBundle\Application\Exception\IdentityException $exception
	 *
	 * @return static
	 */
	public static function fromIdentityException(IdentityException $exception): self
	{
		return new self(sprintf(
			'Identity error: %s',
			$exception->getMessage()
		), self::IDENTITY_ERROR);
	}
}
