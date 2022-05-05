<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\Domain\Exception;

use DomainException;

final class EmailAddressUniquenessException extends DomainException
{
	private string $emailAddress;

	/**
	 * @param string $message
	 * @param string $emailAddress
	 */
	private function __construct(string $message, string $emailAddress)
	{
		parent::__construct($message);

		$this->emailAddress = $emailAddress;
	}

	/**
	 * @param string $emailAddress
	 *
	 * @return static
	 */
	public static function create(string $emailAddress): self
	{
		return new self(sprintf(
			'User with an email "%s" already exists.',
			$emailAddress
		), $emailAddress);
	}

	/**
	 * @return string
	 */
	public function emailAddress(): string
	{
		return $this->emailAddress;
	}
}
