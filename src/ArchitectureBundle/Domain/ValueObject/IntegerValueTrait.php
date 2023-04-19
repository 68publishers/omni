<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject;

use SixtyEightPublishers\ArchitectureBundle\Domain\Exception\InvalidNativeValueTypeException;
use function is_int;

trait IntegerValueTrait
{
    use NativeFactoryMethodTrait;

    protected function __construct(
        protected readonly int $value,
    ) {
    }

    public static function fromSafeNative(mixed $native): static
    {
        if (!is_int($native)) {
            throw InvalidNativeValueTypeException::fromNativeValue($native, 'int', static::class);
        }

        return new static($native);
    }

    public function toNative(): int
    {
        return $this->value;
    }

    public function equals(ValueObjectInterface $object): bool
    {
        return $object instanceof static && $object->toNative() === $this->toNative();
    }
}
