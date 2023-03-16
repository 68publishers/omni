<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject;

use SixtyEightPublishers\ArchitectureBundle\Domain\Exception\InvalidNativeValueTypeException;
use function is_int;

trait NullableIntegerValueTrait
{
    protected function __construct(
        protected readonly ?int $value,
    ) {
    }

    public static function fromNative(mixed $native): static
    {
        if (null !== $native && !is_int($native)) {
            throw InvalidNativeValueTypeException::fromNativeValue($native, 'int|null', static::class);
        }

        return new static($native);
    }

    public static function null(): static
    {
        return new static(null);
    }

    public function toNative(): ?int
    {
        return $this->value;
    }

    public function equals(ValueObjectInterface $object): bool
    {
        return $object instanceof static && $object->toNative() === $this->toNative();
    }

    public function isNull(): bool
    {
        return null === $this->toNative();
    }
}
