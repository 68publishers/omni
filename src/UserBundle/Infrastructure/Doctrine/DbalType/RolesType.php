<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\Infrastructure\Doctrine\DbalType;

use Doctrine\DBAL\Types\JsonType;
use SixtyEightPublishers\ArchitectureBundle\Infrastructure\Doctrine\DbalType\ValueObjectTypeTrait;
use SixtyEightPublishers\UserBundle\Domain\ValueObject\Roles;

final class RolesType extends JsonType
{
    use ValueObjectTypeTrait;

    protected function getValueObjectClassname(): string
    {
        return Roles::class;
    }
}
