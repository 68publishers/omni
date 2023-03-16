<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ForgotPasswordBundle\Infrastructure\Doctrine\DbalType;

use Doctrine\DBAL\Types\StringType;
use SixtyEightPublishers\ArchitectureBundle\Infrastructure\Doctrine\DbalType\ValueObjectTypeTrait;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\ValueObject\IpAddress;

final class IpAddressType extends StringType
{
    use ValueObjectTypeTrait;

    protected function getValueObjectClassname(): string
    {
        return IpAddress::class;
    }
}
