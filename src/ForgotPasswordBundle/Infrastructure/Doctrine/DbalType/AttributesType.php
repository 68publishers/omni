<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ForgotPasswordBundle\Infrastructure\Doctrine\DbalType;

use Doctrine\DBAL\Types\JsonType;
use SixtyEightPublishers\ArchitectureBundle\Infrastructure\Doctrine\DbalType\ValueObjectTypeTrait;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\ValueObject\Attributes;

final class AttributesType extends JsonType
{
    use ValueObjectTypeTrait;

    protected function getValueObjectClassname(): string
    {
        return Attributes::class;
    }
}
