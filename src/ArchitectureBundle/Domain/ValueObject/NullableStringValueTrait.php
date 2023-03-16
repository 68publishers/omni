<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject;

use SixtyEightPublishers\ArchitectureBundle\Domain\Exception\InvalidNativeValueTypeException;
use function is_string;

trait NullableStringValueTrait
{
    protected function __construct(
        protected readonly ?string $value,
    ) {
    }

    public static function fromNative(mixed $native): static
    {
        if (null !== $native && !is_string($native)) {
            throw InvalidNativeValueTypeException::fromNativeValue($native, 'string|null', static::class);
        }

        return new static($native);
    }

    public static function null(): static
    {
        return new static(null);
    }

    public function toNative(): ?string
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
