<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Domain\Exception;

use DomainException;
use SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\ComparableValueObjectInterface;

final class ValueObjectSetException extends DomainException
{
	private string $valueObjectClassname;

	/**
	 * @param string $message
	 * @param string $valueObjectClassname
	 */
	private function __construct(string $message, string $valueObjectClassname)
	{
		parent::__construct($message);

		$this->valueObjectClassname = $valueObjectClassname;
	}

	/**
	 * @param string $valueObjectClassname
	 * @param string $itemType
	 *
	 * @return static
	 */
	public static function declaredItemTypeMustBeComparable(string $valueObjectClassname, string $itemType): self
	{
		return new self(sprintf(
			'Invalid item type %s declared for a value object set %s. Item type must implements an interface %s.',
			$itemType,
			$valueObjectClassname,
			ComparableValueObjectInterface::class
		), $valueObjectClassname);
	}

	/**
	 * @param string $valueObjectClassname
	 * @param string $expectedItemType
	 * @param string $passedItemType
	 *
	 * @return static
	 */
	public static function invalidItemTypePassed(string $valueObjectClassname, string $expectedItemType, string $passedItemType): self
	{
		return new self(sprintf(
			'Invalid item\'s passed into a value object of type %s. Expected item\'s type is %s, instance of %s passed.',
			$valueObjectClassname,
			$expectedItemType,
			$passedItemType
		), $valueObjectClassname);
	}

	/**
	 * @return string
	 */
	public function valueObjectClassname(): string
	{
		return $this->valueObjectClassname;
	}
}
