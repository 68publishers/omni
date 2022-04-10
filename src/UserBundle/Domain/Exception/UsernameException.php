<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\Domain\Exception;

use DomainException;

final class UsernameException extends DomainException
{
	public const DUPLICATED_VALUE = 2;

	private string $username;

	/**
	 * @param string $message
	 * @param string $emailAddress
	 * @param int    $code
	 */
	private function __construct(string $message, string $emailAddress, int $code)
	{
		parent::__construct($message, $code);

		$this->username = $emailAddress;
	}

	/**
	 * @param string $username
	 *
	 * @return static
	 */
	public static function duplicatedValue(string $username): self
	{
		return new self(sprintf(
			'User with an username "%s" already exists.',
			$username
		), $username, self::DUPLICATED_VALUE);
	}

	/**
	 * @return string
	 */
	public function username(): string
	{
		return $this->username;
	}
}
