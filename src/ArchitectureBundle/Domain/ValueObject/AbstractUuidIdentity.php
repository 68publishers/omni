<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Ramsey\Uuid\Exception\UuidExceptionInterface;
use SixtyEightPublishers\ArchitectureBundle\Domain\Exception\InvalidIdentityValueException;

abstract class AbstractUuidIdentity implements IdentityInterface
{
	protected UuidInterface $uuid;

	private function __construct()
	{
	}

	/**
	 * {@inheritDoc}
	 */
	public static function fromString(string $id): self
	{
		try {
			$identity = new static();
			$identity->uuid = Uuid::fromString($id);

			return $identity;
		} catch (UuidExceptionInterface $e) {
			throw InvalidIdentityValueException::create($id, static::class);
		}
	}

	/**
	 * {@inheritDoc}
	 */
	public static function isValid(string $id): bool
	{
		return Uuid::isValid($id);
	}

	/**
	 * @param \Ramsey\Uuid\UuidInterface $uuid
	 *
	 * @return static
	 */
	public static function fromUuid(UuidInterface $uuid): self
	{
		$identity = new static();
		$identity->uuid = $uuid;

		return $identity;
	}

	/**
	 * @return static
	 */
	public static function new(): self
	{
		$identity = new static();
		$identity->uuid = Uuid::uuid4();

		return $identity;
	}

	/**
	 * @return \Ramsey\Uuid\UuidInterface
	 */
	public function id(): UuidInterface
	{
		return $this->uuid;
	}

	/**
	 * {@inheritDoc}
	 */
	public function toString(): string
	{
		return $this->id()->toString();
	}

	/**
	 * {@inheritDoc}
	 */
	public function __toString(): string
	{
		return $this->toString();
	}

	/**
	 * {@inheritDoc}
	 */
	public function equals(ComparableValueObjectInterface $valueObject): bool
	{
		return $valueObject instanceof static && $valueObject->toString() === $this->toString();
	}
}
