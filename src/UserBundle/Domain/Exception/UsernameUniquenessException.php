<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\Domain\Exception;

use DomainException;

final class UsernameUniquenessException extends DomainException
{
	private string $username;

	/**
	 * @param string $message
	 * @param string $username
	 */
	private function __construct(string $message, string $username)
	{
		parent::__construct($message);

		$this->username = $username;
	}

	/**
	 * @param string $username
	 *
	 * @return static
	 */
	public static function create(string $username): self
	{
		return new self(sprintf(
			'User with a username "%s" already exists.',
			$username
		), $username);
	}

	/**
	 * @return string
	 */
	public function username(): string
	{
		return $this->username;
	}
}
