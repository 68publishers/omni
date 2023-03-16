<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\Domain\ValueObject;

use SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\ValueObjectInterface;
use SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\ValueObjectSetTrait;

final class Roles implements ValueObjectInterface
{
    use ValueObjectSetTrait;

    protected static function getItemClassname(): string
    {
        return Role::class;
    }
}
