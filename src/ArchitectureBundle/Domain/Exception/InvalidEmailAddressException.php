<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Domain\Exception;

use DomainException;
use function sprintf;

final class InvalidEmailAddressException extends DomainException
{
    /**
     * @param class-string $valueObjectClassname
     */
    public function __construct(
        string $message,
        public readonly string $emailAddress,
        public readonly string $valueObjectClassname,
    ) {
        parent::__construct($message, 0);
    }

    /**
     * @param class-string $valueObjectClassname
     */
    public static function create(string $emailAddress, string $valueObjectClassname): self
    {
        return new self(sprintf(
            'Value %s is not valid email for a value object of the type %s.',
            $emailAddress,
            $valueObjectClassname,
        ), $emailAddress, $valueObjectClassname);
    }
}
