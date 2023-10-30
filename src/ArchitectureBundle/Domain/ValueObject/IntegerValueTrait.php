<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject;

use SixtyEightPublishers\ArchitectureBundle\Domain\Exception\InvalidNativeValueTypeException;
use function assert;
use function is_int;

trait IntegerValueTrait
{
    protected function __construct(
        protected readonly int $value,
    ) {
    }

    public static function fromNative(mixed $native): static
    {
        if (!is_int($native)) {
            throw InvalidNativeValueTypeException::fromNativeValue($native, 'int', static::class);
        }

        $valueObject = new static($native);

        $valueObject->validate();

        return $valueObject;
    }

    public static function fromSafeNative(mixed $native): static
    {
        assert(is_int($native));

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

    protected function validate(): void
    {
    }
}
