<?php

declare(strict_types=1);

namespace SixtyEightPublishers\MailingBundle\Application\Exception;

use InvalidArgumentException;
use function sprintf;

final class InvalidEmailAddressException extends InvalidArgumentException
{
    public function __construct(
        string $message,
        public readonly string $emailAddress,
    ) {
        parent::__construct($message, 0);
    }

    public static function create(string $emailAddress): self
    {
        return new self(sprintf(
            'Value %s is not valid email.',
            $emailAddress,
        ), $emailAddress);
    }
}
