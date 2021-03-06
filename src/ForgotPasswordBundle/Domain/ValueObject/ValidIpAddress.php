<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ForgotPasswordBundle\Domain\ValueObject;

use SixtyEightPublishers\ForgotPasswordBundle\Domain\Exception\IpAddressException;
use SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\AbstractStringValueObject;

final class ValidIpAddress extends AbstractStringValueObject implements IpAddressInterface
{
	/**
	 * @param string $value
	 * @param bool   $allowEmpty
	 *
	 * @return static
	 */
	public static function fromValue(string $value, bool $allowEmpty = FALSE): self
	{
		if ($allowEmpty && empty($value)) {
			return parent::fromValue($value);
		}

		if (!filter_var($value, FILTER_VALIDATE_IP)) {
			throw IpAddressException::invalidValue($value);
		}

		return parent::fromValue($value);
	}

	/**
	 * @param \SixtyEightPublishers\ForgotPasswordBundle\Domain\ValueObject\IpAddressInterface $ipAddress
	 *
	 * @return static
	 */
	public static function fromInstance(IpAddressInterface $ipAddress): self
	{
		return self::fromValue($ipAddress->value());
	}
}
