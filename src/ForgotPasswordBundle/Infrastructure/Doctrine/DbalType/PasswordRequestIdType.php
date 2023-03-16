<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ForgotPasswordBundle\Infrastructure\Doctrine\DbalType;

use Doctrine\DBAL\Types\GuidType;
use SixtyEightPublishers\ArchitectureBundle\Infrastructure\Doctrine\DbalType\ValueObjectTypeTrait;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\ValueObject\PasswordRequestId;

final class PasswordRequestIdType extends GuidType
{
    use ValueObjectTypeTrait;

    protected function getValueObjectClassname(): string
    {
        return PasswordRequestId::class;
    }
}
