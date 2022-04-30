<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ForgotPasswordBundle\Domain\Exception;

use DomainException;

final class EmailAddressNotFoundException extends DomainException
{
	/**
	 * @param string $message
	 */
	private function __construct(string $message)
	{
		parent::__construct($message);
	}

	/**
	 * @param string $emailAddress
	 *
	 * @return static
	 */
	public static function create(string $emailAddress): self
	{
		return new self(sprintf(
			'The user with an email address %s not found.',
			$emailAddress
		));
	}
}
