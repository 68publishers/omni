<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject;

use SixtyEightPublishers\ArchitectureBundle\Domain\Exception\InvalidNativeValueTypeException;
use function is_float;

trait NullableFloatValueTrait
{
    protected function __construct(
        protected readonly ?float $value,
    ) {
    }

    public static function fromNative(mixed $native): static
    {
        if (null !== $native && !is_float($native)) {
            throw InvalidNativeValueTypeException::fromNativeValue($native, 'float|null', static::class);
        }

        return new static($native);
    }

    public static function null(): static
    {
        return new static(null);
    }

    public function toNative(): ?float
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
