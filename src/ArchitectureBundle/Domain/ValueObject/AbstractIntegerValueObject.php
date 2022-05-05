<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject;

abstract class AbstractIntegerValueObject implements IntegerValueObjectInterface, ComparableValueObjectInterface
{
	protected int $value;

	private function __construct()
	{
	}

	/**
	 * {@inheritDoc}
	 */
	public static function fromValue(int $value): self
	{
		$valueObject = new static();
		$valueObject->value = $value;

		return $valueObject;
	}

	/**
	 * {@inheritDoc}
	 */
	public function value(): int
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
}
