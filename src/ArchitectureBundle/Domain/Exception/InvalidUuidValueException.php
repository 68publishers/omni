<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Domain\Exception;

use DomainException;
use function sprintf;

final class InvalidUuidValueException extends DomainException
{
    /**
     * @param class-string $valueObjectClassname
     */
    public function __construct(
        string $message,
        public readonly string $identifier,
        public readonly string $valueObjectClassname,
    ) {
        parent::__construct($message);
    }

    /**
     * @param class-string $valueObjectClassname
     */
    public static function create(string $identifier, string $valueObjectClassname): self
    {
        return new self(sprintf(
            'Invalid uuid value %s for a value object of the type %s.',
            $identifier,
            $valueObjectClassname,
        ), $identifier, $valueObjectClassname);
    }
}
