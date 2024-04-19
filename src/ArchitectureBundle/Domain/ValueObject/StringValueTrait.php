<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject;

use SixtyEightPublishers\ArchitectureBundle\Domain\Exception\InvalidNativeValueTypeException;
use function assert;
use function is_string;

trait StringValueTrait
{
    protected function __construct(
        protected readonly string $value,
    ) {
    }

    public static function fromNative(mixed $native): static
    {
        if (!is_string($native)) {
            throw InvalidNativeValueTypeException::fromNativeValue($native, 'string', static::class);
        }

        $valueObject = new static($native);

        $valueObject->validate();

        return $valueObject;
    }

    public static function fromSafeNative(mixed $native): static
    {
        assert(is_string($native));

        return new static($native);
    }

    public function toNative(): string
    {
        return $this->value;
    }

    public function equals(ValueObjectInterface $object): bool
    {
        return $object instanceof static && $object->toNative() === $this->toNative();
    }

    public function toString(): string
    {
        return $this->toNative();
    }

    public function __toString(): string
    {
        return $this->toString();
    }

    protected function validate(): void
    {
    }
}
