<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject;

interface CompositeAggregateIdInterface extends AggregateIdInterface
{
    /**
     * @return non-empty-array<string, class-string<ValueObjectInterface>>
     */
    public static function getStructure(): array;

    /**
     * @return non-empty-array<string, ValueObjectInterface>
     */
    public function getValues(): array;
}
