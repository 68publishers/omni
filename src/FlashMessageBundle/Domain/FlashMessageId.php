<?php

declare(strict_types=1);

namespace SixtyEightPublishers\FlashMessageBundle\Domain;

use SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\UuidValueTrait;
use SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\ValueObjectInterface;

final class FlashMessageId implements ValueObjectInterface
{
    use UuidValueTrait;
}
