<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject;

use SixtyEightPublishers\ArchitectureBundle\Domain\Exception\InvalidEnumValueException;

abstract class AbstractEnumValueObject implements StringValueObjectInterface, ComparableValueObjectInterface
{
	private string $value;

	private function __construct()
	{
	}

	/**
	 * @param string $value
	 *
	 * @return static
	 */
	public static function fromValue(string $value): self
	{
		if (!in_array($value, static::values(), TRUE)) {
			throw InvalidEnumValueException::create(static::class, $value, static::values());
		}

		$valueObject = new static();
		$valueObject->value = $value;

		return $valueObject;
	}

	/**
	 * @return string[]
	 */
	public static function values(): array
	{
		return [];
	}

	/**
	 * {@inheritDoc}
	 */
	public function value(): string
	{
		return $this->value;
	}

	/**
	 * {@inheritDoc}
	 */
	public function equals(ComparableValueObjectInterface $valueObject): bool
	{
		return $valueObject instanceof static && $valueObject->value() === $this->value();
	}

	/**
	 * @param string $value
	 *
	 * @return bool
	 */
	public function is(string $value): bool
	{
		return $this->value === $value;
	}
}
