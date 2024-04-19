<?php

declare(strict_types=1);

namespace SixtyEightPublishers\MailingBundle\Domain\ValueObject;

use SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\AggregateIdInterface;
use SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\UuidValueTrait;

final class MailId implements AggregateIdInterface
{
    use UuidValueTrait;
}
