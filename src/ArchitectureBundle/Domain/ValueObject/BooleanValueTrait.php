<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject;

use SixtyEightPublishers\ArchitectureBundle\Domain\Exception\InvalidNativeValueTypeException;
use function assert;

trait BooleanValueTrait
{
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

    public static function fromNative(mixed $native): static
    {
        if (!is_bool($native)) {
            throw InvalidNativeValueTypeException::fromNativeValue($native, 'bool', static::class);
        }

        $valueObject = new static($native);

        $valueObject->validate();

        return $valueObject;
    }

    public static function fromSafeNative(mixed $native): static
    {
        assert(\is_bool($native));

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

    protected function validate(): void
    {
    }
}
