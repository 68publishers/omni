<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\Domain\Exception;

use DomainException;

final class EmailAddressException extends DomainException
{
	public const INVALID_VALUE = 1;
	public const DUPLICATED_VALUE = 2;

	private string $emailAddress;

	/**
	 * @param string $message
	 * @param string $emailAddress
	 * @param int    $code
	 */
	private function __construct(string $message, string $emailAddress, int $code)
	{
		parent::__construct($message, $code);

		$this->emailAddress = $emailAddress;
	}

	/**
	 * @param string $emailAddress
	 *
	 * @return static
	 */
	public static function invalidValue(string $emailAddress): self
	{
		return new self(sprintf(
			'Value %s is not valid email.',
			$emailAddress
		), $emailAddress, self::INVALID_VALUE);
	}

	/**
	 * @param string $emailAddress
	 *
	 * @return static
	 */
	public static function duplicatedValue(string $emailAddress): self
	{
		return new self(sprintf(
			'User with an email "%s" already exists.',
			$emailAddress
		), $emailAddress, self::DUPLICATED_VALUE);
	}

	/**
	 * @return string
	 */
	public function emailAddress(): string
	{
		return $this->emailAddress;
	}
}
