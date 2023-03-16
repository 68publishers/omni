<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ForgotPasswordBundle\Domain\Exception;

use DomainException;
use function sprintf;

final class IpAddressException extends DomainException
{
    private function __construct(
        string $message,
        public readonly string $value,
    ) {
        parent::__construct($message);
    }

    public static function invalidValue(string $value): self
    {
        return new self(sprintf(
            'Value %s is not valid IP address.',
            $value,
        ), $value);
    }
}
