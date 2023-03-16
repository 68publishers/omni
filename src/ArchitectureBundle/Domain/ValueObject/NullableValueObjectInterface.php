<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject;

interface NullableValueObjectInterface extends ValueObjectInterface
{
    public function isNull(): bool;
}
