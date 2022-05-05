<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject;

interface IdentityInterface extends ComparableValueObjectInterface
{
	/**
	 * @param string $id
	 *
	 * @return static
	 * @throws \SixtyEightPublishers\ArchitectureBundle\Domain\Exception\InvalidIdentityValueException
	 */
	public static function fromString(string $id): self;

	/**
	 * @param string $id
	 *
	 * @return bool
	 */
	public static function isValid(string $id): bool;

	/**
	 * @return mixed
	 */
	public function id();

	/**
	 * @return string
	 */
	public function toString(): string;

	/**
	 * @return string
	 */
	public function __toString(): string;
}
