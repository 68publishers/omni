<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ForgotPasswordBundle\Domain\Exception;

use DomainException;

final class IpAddressException extends DomainException
{
	public const INVALID_VALUE = 1;
	
	private string $ipAddress;

	/**
	 * @param string $message
	 * @param string $ipAddress
	 * @param int    $code
	 */
	private function __construct(string $message, string $ipAddress, int $code)
	{
		parent::__construct($message, $code);

		$this->ipAddress = $ipAddress;
	}

	/**
	 * @param string $ipAddress
	 *
	 * @return static
	 */
	public static function invalidValue(string $ipAddress): self
	{
		return new self(sprintf(
			'Value %s is not valid IP address.',
			$ipAddress
		), $ipAddress, self::INVALID_VALUE);
	}

	/**
	 * @return string
	 */
	public function ipAddress(): string
	{
		return $this->ipAddress;
	}
}
