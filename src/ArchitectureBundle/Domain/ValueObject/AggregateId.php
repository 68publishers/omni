<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject;

final class AggregateId implements ValueObjectInterface
{
    use UuidValueTrait;
}
