<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject;

interface AggregateIdInterface extends ValueObjectInterface
{
    public static function isValid(mixed $native): bool;

    public function toString(): string;
}
