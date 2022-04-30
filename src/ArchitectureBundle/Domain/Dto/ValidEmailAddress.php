<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Domain\Dto;

use Nette\Utils\Validators;
use SixtyEightPublishers\UserBundle\Domain\Exception\EmailAddressException;

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
	 * @param \SixtyEightPublishers\ArchitectureBundle\Domain\Dto\EmailAddressInterface $emailAddress
	 *
	 * @return static
	 */
	public static function fromInstance(EmailAddressInterface $emailAddress): self
	{
		return self::fromValue($emailAddress->value());
	}
}
