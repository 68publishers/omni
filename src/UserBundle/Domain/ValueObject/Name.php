<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\Domain\ValueObject;

use SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\ComparableValueObjectInterface;

final class Name implements ComparableValueObjectInterface
{
	private string $firstname;

	private string $surname;

	private function __construct()
	{
	}

	/**
	 * @param string $firstname
	 * @param string $surname
	 *
	 * @return static
	 */
	public static function fromValues(string $firstname, string $surname): self
	{
		$name = new self();
		$name->firstname = $firstname;
		$name->surname = $surname;

		return $name;
	}

	/**
	 * @return string
	 */
	public function firstname(): string
	{
		return $this->firstname;
	}

	/**
	 * @return string
	 */
	public function surname(): string
	{
		return $this->surname;
	}

	/**
	 * @return string
	 */
	public function name(): string
	{
		return implode(' ', array_filter([$this->firstname(), $this->surname()], static fn (string $part): bool => !empty($part)));
	}

	/**
	 * {@inheritDoc}
	 */
	public function equals(ComparableValueObjectInterface $valueObject): bool
	{
		return $valueObject instanceof self && $valueObject->firstname() === $this->firstname() && $valueObject->surname() === $this->surname();
	}
}
