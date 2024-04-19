<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject;

/**
 * @deprecated
 */
final class AggregateId implements AggregateIdInterface
{
    use UuidValueTrait;
}
