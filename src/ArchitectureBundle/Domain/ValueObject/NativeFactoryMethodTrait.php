<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject;

trait NativeFactoryMethodTrait
{
    abstract public static function fromSafeNative(mixed $native): static;

    public static function fromNative(mixed $native): static
    {
        $valueObject = static::fromSafeNative($native);

        $valueObject->validate();

        return $valueObject;
    }

    protected function validate(): void
    {
    }
}
