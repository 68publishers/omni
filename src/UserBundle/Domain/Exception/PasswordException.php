<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\Domain\Exception;

use DomainException;

final class PasswordException extends DomainException
{
	public const EMPTY_PASSWORD = 1;
	public const CANT_HASH_PASSWORD = 2;

	/**
	 * @param string $message
	 * @param int    $code
	 */
	private function __construct(string $message, int $code)
	{
		parent::__construct($message, $code);
	}

	/**
	 * @return static
	 */
	public static function emptyPassword(): self
	{
		return new self('Password can\'t be empty.', self::EMPTY_PASSWORD);
	}

	/**
	 * @param string $reason
	 *
	 * @return static
	 */
	public static function cantHashPassword(string $reason): self
	{
		return new self(sprintf(
			'Can\'t hash a password. %s',
			$reason
		), self::CANT_HASH_PASSWORD);
	}
}
