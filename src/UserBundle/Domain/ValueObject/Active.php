<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\Domain\ValueObject;

use SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\BooleanValueTrait;
use SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\ValueObjectInterface;

final class Active implements ValueObjectInterface
{
    use BooleanValueTrait;
}
