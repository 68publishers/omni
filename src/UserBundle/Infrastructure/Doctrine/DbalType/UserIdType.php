<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\Infrastructure\Doctrine\DbalType;

use Doctrine\DBAL\Types\GuidType;
use SixtyEightPublishers\ArchitectureBundle\Infrastructure\Doctrine\DbalType\ValueObjectTypeTrait;
use SixtyEightPublishers\UserBundle\Domain\ValueObject\UserId;

final class UserIdType extends GuidType
{
    use ValueObjectTypeTrait;

    protected function getValueObjectClassname(): string
    {
        return UserId::class;
    }
}
