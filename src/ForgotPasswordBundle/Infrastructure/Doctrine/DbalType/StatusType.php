<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ForgotPasswordBundle\Infrastructure\Doctrine\DbalType;

use SixtyEightPublishers\ArchitectureBundle\Infrastructure\Doctrine\DbalType\AbstractEnumType;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\ValueObject\Status;

final class StatusType extends AbstractEnumType
{
    protected function getEnumsClassname(): string
    {
        return Status::class;
    }
}
