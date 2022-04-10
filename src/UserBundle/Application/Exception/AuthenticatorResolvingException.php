<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\Application\Exception;

use Exception;

final class AuthenticatorResolvingException extends Exception
{
	/**
	 * @param string $message
	 */
	private function __construct(string $message)
	{
		parent::__construct($message);
	}

	/**
	 * @return $this
	 */
	public static function missingDefault(): self
	{
		return new self('Can\'t authenticate a user because the default authenticator is missing.');
	}

	/**
	 * @param string $name
	 *
	 * @return static
	 */
	public static function missingAuthenticator(string $name): self
	{
		return new self(sprintf(
			'Can\'t authenticate a user because an authenticator with the name %s is missing.',
			$name
		));
	}
}
