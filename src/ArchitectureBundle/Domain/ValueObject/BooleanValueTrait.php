<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject;

use SixtyEightPublishers\ArchitectureBundle\Domain\Exception\InvalidNativeValueTypeException;

trait BooleanValueTrait
{
    use NativeFactoryMethodTrait;

    protected function __construct(
        protected readonly bool $value,
    ) {
    }

    public static function true(): static
    {
        return new static(true);
    }

    public static function false(): static
    {
        return new static(false);
    }

    public static function fromSafeNative(mixed $native): static
    {
        if (!is_bool($native)) {
            throw InvalidNativeValueTypeException::fromNativeValue($native, 'bool', static::class);
        }

        return new static($native);
    }

    public function toNative(): bool
    {
        return $this->value;
    }

    public function equals(ValueObjectInterface $object): bool
    {
        return $object instanceof static && $object->toNative() === $this->toNative();
    }

    public function isTrue(): bool
    {
        return $this->toNative();
    }

    public function isFalse(): bool
    {
        return !$this->toNative();
    }
}
