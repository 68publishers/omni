<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\Domain\ValueObject;

use SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\AggregateIdInterface;
use SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\UuidValueTrait;

final class UserId implements AggregateIdInterface
{
    use UuidValueTrait;
}
