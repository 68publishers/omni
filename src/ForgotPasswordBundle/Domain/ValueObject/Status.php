<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ForgotPasswordBundle\Domain\ValueObject;

enum Status: string
{
    case REQUESTED = 'requested';
    case COMPLETED = 'completed';
    case CANCELED = 'canceled';

    public function isFinished(): bool
    {
        return $this === self::COMPLETED || $this === self::CANCELED;
    }
}
