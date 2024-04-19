<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ForgotPasswordBundle\Domain\ValueObject;

use SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\AggregateIdInterface;
use SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\UuidValueTrait;

final class PasswordRequestId implements AggregateIdInterface
{
    use UuidValueTrait;
}
