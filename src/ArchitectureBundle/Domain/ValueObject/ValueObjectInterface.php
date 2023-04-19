<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject;

interface ValueObjectInterface
{
    /**
     * Performs type and custom validation checks
     */
    public static function fromNative(mixed $native): static;

    /**
     * Performs type checks only. Used for hydration from safe side of the application (e.g. database)
     */
    public static function fromSafeNative(mixed $native): static;

    public function toNative(): mixed;

    public function equals(ValueObjectInterface $object): bool;
}
