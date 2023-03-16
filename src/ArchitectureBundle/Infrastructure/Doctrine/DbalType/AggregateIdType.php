<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Infrastructure\Doctrine\DbalType;

use Doctrine\DBAL\Types\GuidType;
use SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\AggregateId;

final class AggregateIdType extends GuidType
{
    use ValueObjectTypeTrait;

    protected function getValueObjectClassname(): string
    {
        return AggregateId::class;
    }
}
