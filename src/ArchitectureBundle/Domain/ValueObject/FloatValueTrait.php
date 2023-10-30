<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject;

use SixtyEightPublishers\ArchitectureBundle\Domain\Exception\InvalidNativeValueTypeException;
use function assert;
use function is_float;

trait FloatValueTrait
{
    protected function __construct(
        protected readonly float $value,
    ) {
    }

    public static function fromNative(mixed $native): static
    {
        if (!is_float($native)) {
            throw InvalidNativeValueTypeException::fromNativeValue($native, 'float', static::class);
        }

        $valueObject = new self($native);

        $valueObject->validate();

        return $valueObject;
    }

    public static function fromSafeNative(mixed $native): static
    {
        assert(is_float($native));

        return new static($native);
    }

    public function toNative(): float
    {
        return $this->value;
    }

    public function equals(ValueObjectInterface $object): bool
    {
        return $object instanceof static && $object->toNative() === $this->toNative();
    }

    protected function validate(): void
    {
    }
}
