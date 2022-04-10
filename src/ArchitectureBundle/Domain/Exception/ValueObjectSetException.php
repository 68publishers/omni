<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Domain\Exception;

use DomainException;
use SixtyEightPublishers\ArchitectureBundle\Domain\Dto\ComparableValueObjectInterface;

final class ValueObjectSetException extends DomainException
{
	private string $dtoClassname;

	/**
	 * @param string $message
	 * @param string $dtoClassname
	 */
	private function __construct(string $message, string $dtoClassname)
	{
		parent::__construct($message);

		$this->dtoClassname = $dtoClassname;
	}

	/**
	 * @param string $dtoClassname
	 * @param string $itemType
	 *
	 * @return static
	 */
	public static function declaredItemTypeMustBeComparable(string $dtoClassname, string $itemType): self
	{
		return new self(sprintf(
			'Invalid item type %s declared for a value object set %s. Item type must implements an interface %s.',
			$itemType,
			$dtoClassname,
			ComparableValueObjectInterface::class
		), $dtoClassname);
	}

	/**
	 * @param string $dtoClassname
	 * @param string $expectedItemType
	 * @param string $passedItemType
	 *
	 * @return static
	 */
	public static function invalidItemTypePassed(string $dtoClassname, string $expectedItemType, string $passedItemType): self
	{
		return new self(sprintf(
			'Invalid item\'s passed into a value object of type %s. Expected item\'s type is %s, instance of %s passed.',
			$dtoClassname,
			$expectedItemType,
			$passedItemType
		), $dtoClassname);
	}

	/**
	 * @return string
	 */
	public function getDtoClassname(): string
	{
		return $this->dtoClassname;
	}
}
