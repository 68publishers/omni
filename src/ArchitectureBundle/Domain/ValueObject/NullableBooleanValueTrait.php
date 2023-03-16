<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject;

use SixtyEightPublishers\ArchitectureBundle\Domain\Exception\InvalidNativeValueTypeException;
use function is_bool;

trait NullableBooleanValueTrait
{
    protected function __construct(
        protected readonly ?bool $value,
    ) {
    }

    public static function null(): static
    {
        return new static(null);
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
        if (null !== $native && !is_bool($native)) {
            throw InvalidNativeValueTypeException::fromNativeValue($native, 'bool|null', static::class);
        }

        return new static($native);
    }

    public function toNative(): ?bool
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

    public function isTrue(): bool
    {
        return $this->toNative();
    }

    public function isFalse(): bool
    {
        return !$this->toNative();
    }
}
