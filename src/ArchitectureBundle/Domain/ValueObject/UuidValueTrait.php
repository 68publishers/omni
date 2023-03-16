<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject;

use Ramsey\Uuid\Exception\UuidExceptionInterface;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use SixtyEightPublishers\ArchitectureBundle\Domain\Exception\InvalidNativeValueTypeException;
use SixtyEightPublishers\ArchitectureBundle\Domain\Exception\InvalidUuidValueException;
use function is_string;

trait UuidValueTrait
{
    protected function __construct(
        protected readonly UuidInterface $value,
    ) {
    }

    public static function new(): static
    {
        return new static(Uuid::uuid4());
    }

    public static function fromUuid(UuidInterface $uuid): self
    {
        return new static($uuid);
    }

    public static function fromNative(mixed $native): static
    {
        if (!is_string($native)) {
            throw InvalidNativeValueTypeException::fromNativeValue($native, 'string', static::class);
        }

        try {
            return new static(Uuid::fromString($native));
        } catch (UuidExceptionInterface $e) {
            throw InvalidUuidValueException::create($native, static::class);
        }
    }

    public static function isValid(mixed $native): bool
    {
        return is_string($native) && Uuid::isValid($native);
    }

    public function toUuid(): UuidInterface
    {
        return $this->value;
    }

    public function toNative(): string
    {
        return $this->value->toString();
    }

    public function equals(ValueObjectInterface $object): bool
    {
        return $object instanceof static && $object->toUuid()->equals($this->toUuid());
    }

    public function __toString(): string
    {
        return $this->toNative();
    }
}
