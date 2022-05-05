<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject;

use Nette\Utils\Validators;
use SixtyEightPublishers\ArchitectureBundle\Domain\Exception\EmailAddressException;

final class ValidEmailAddress extends AbstractStringValueObject implements EmailAddressInterface
{
	/**
	 * {@inheritDoc}
	 */
	public static function fromValue(string $value): AbstractStringValueObject
	{
		if (!Validators::isEmail($value)) {
			throw EmailAddressException::invalidValue($value);
		}

		return parent::fromValue($value);
	}

	/**
	 * @param \SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\EmailAddressInterface $emailAddress
	 *
	 * @return static
	 */
	public static function fromInstance(EmailAddressInterface $emailAddress): self
	{
		return self::fromValue($emailAddress->value());
	}
}
