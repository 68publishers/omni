<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\Domain\ValueObject;

use SixtyEightPublishers\UserBundle\Domain\PasswordHashAlgorithmInterface;
use SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\StringValueObjectInterface;

final class Password implements StringValueObjectInterface
{
	private string $value;

	private function __construct()
	{
	}

	/**
	 * {@inheritDoc}
	 */
	public static function fromValue(string $value): self
	{
		$password = new self();
		$password->value = $value;

		return $password;
	}

	/**
	 * {@inheritDoc}
	 */
	public function value(): string
	{
		return $this->value;
	}

	/**
	 * @param \SixtyEightPublishers\UserBundle\Domain\PasswordHashAlgorithmInterface $algorithm
	 *
	 * @return \SixtyEightPublishers\UserBundle\Domain\ValueObject\HashedPassword
	 */
	public function createHashedPassword(PasswordHashAlgorithmInterface $algorithm): HashedPassword
	{
		return HashedPassword::fromValue(
			$algorithm->hash($this->value())
		);
	}
}
