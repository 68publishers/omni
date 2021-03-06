<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\Domain\ValueObject;

use SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\StringValueObjectInterface;

final class HashedPassword implements StringValueObjectInterface
{
	private string $hash;

	private function __construct()
	{
	}

	/**
	 * {@inheritDoc}
	 */
	public static function fromValue(string $value): self
	{
		$hashedPassword = new self();
		$hashedPassword->hash = $value;

		return $hashedPassword;
	}

	/**
	 * {@inheritDoc}
	 */
	public function value(): string
	{
		return $this->hash;
	}

	/**
	 * @param \SixtyEightPublishers\UserBundle\Domain\ValueObject\Password $password
	 *
	 * @return bool
	 */
	public function verify(Password $password): bool
	{
		return password_verify($password->value(), $this->value());
	}
}
