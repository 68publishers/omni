<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ForgotPasswordBundle\Domain\ValueObject;

use SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\AbstractEnumValueObject;

final class Status extends AbstractEnumValueObject
{
	public const REQUESTED = 'requested';
	public const COMPLETED = 'completed';
	public const CANCELED = 'canceled';

	/**
	 * {@inheritDoc}
	 */
	public static function values(): array
	{
		return [
			self::REQUESTED,
			self::COMPLETED,
			self::CANCELED,
		];
	}

	/**
	 * @return $this
	 */
	public static function REQUESTED(): self
	{
		return self::fromValue(self::REQUESTED);
	}

	/**
	 * @return static
	 */
	public static function COMPLETED(): self
	{
		return self::fromValue(self::COMPLETED);
	}

	/**
	 * @return static
	 */
	public static function CANCELED(): self
	{
		return self::fromValue(self::CANCELED);
	}

	/**
	 * @return bool
	 */
	public function isFinished(): bool
	{
		return $this->is(self::COMPLETED) || $this->is(self::CANCELED);
	}
}
