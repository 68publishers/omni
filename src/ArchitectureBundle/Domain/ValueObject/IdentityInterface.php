<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject;

interface IdentityInterface extends ComparableValueObjectInterface
{
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
