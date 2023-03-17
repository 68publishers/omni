<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\Infrastructure\Doctrine\DbalType;

use Doctrine\DBAL\Types\StringType;
use SixtyEightPublishers\ArchitectureBundle\Infrastructure\Doctrine\DbalType\ValueObjectTypeTrait;
use SixtyEightPublishers\UserBundle\Domain\ValueObject\Firstname;

final class FirstnameType extends StringType
{
    use ValueObjectTypeTrait;

    protected function getValueObjectClassname(): string
    {
        return Firstname::class;
    }
}