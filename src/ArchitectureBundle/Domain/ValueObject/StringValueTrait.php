<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject;

use SixtyEightPublishers\ArchitectureBundle\Domain\Exception\InvalidNativeValueTypeException;
use function is_string;

trait StringValueTrait
{
    use NativeFactoryMethodTrait;

    protected function __construct(
        protected readonly string $value,
    ) {
    }

    public static function fromSafeNative(mixed $native): static
    {
        if (!is_string($native)) {
            throw InvalidNativeValueTypeException::fromNativeValue($native, 'string', static::class);
        }

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
}
