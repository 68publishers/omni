<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\Infrastructure\Doctrine\DbalType;

use Doctrine\DBAL\Types\BooleanType;
use SixtyEightPublishers\ArchitectureBundle\Infrastructure\Doctrine\DbalType\ValueObjectTypeTrait;
use SixtyEightPublishers\UserBundle\Domain\ValueObject\Active;

final class ActiveType extends BooleanType
{
    use ValueObjectTypeTrait;

    protected function getValueObjectClassname(): string
    {
        return Active::class;
    }
}
