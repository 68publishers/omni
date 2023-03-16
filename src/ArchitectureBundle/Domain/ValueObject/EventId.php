<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject;

final class EventId implements ValueObjectInterface
{
    use UuidValueTrait;
}
