<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\Domain\ValueObject;

use SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\StringValueTrait;
use SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\ValueObjectInterface;

final class Username implements ValueObjectInterface
{
    use StringValueTrait;
}
