<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject;

interface ValueObjectInterface
{
    public static function fromNative(mixed $native): static;

    public function toNative(): mixed;

    public function equals(ValueObjectInterface $object): bool;
}
