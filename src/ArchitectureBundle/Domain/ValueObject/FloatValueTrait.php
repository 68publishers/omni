<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject;

use SixtyEightPublishers\ArchitectureBundle\Domain\Exception\InvalidNativeValueTypeException;
use function is_float;

trait FloatValueTrait
{
    use NativeFactoryMethodTrait;

    protected function __construct(
        protected readonly float $value,
    ) {
    }

    public static function fromSafeNative(mixed $native): static
    {
        if (!is_float($native)) {
            throw InvalidNativeValueTypeException::fromNativeValue($native, 'float', static::class);
        }

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
}
