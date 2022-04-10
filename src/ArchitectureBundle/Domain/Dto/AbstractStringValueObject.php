<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Domain\Dto;

abstract class AbstractStringValueObject implements StringValueObjectInterface, ComparableValueObjectInterface
{
	protected string $value;

	private function __construct()
	{
	}

	/**
	 * {@inheritDoc}
	 */
	public static function fromValue(string $value): self
	{
		$valueObject = new static();
		$valueObject->value = $value;

		return $valueObject;
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
}
