<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Domain\Exception;

use DomainException;
use function mb_strlen;
use function sprintf;

final class MaximumLengthExceededException extends DomainException
{
    /**
     * @param class-string $valueObjectClassname
     */
    public function __construct(
        string $message,
        public readonly string $value,
        public readonly int $maximumLength,
        public readonly int $valueLength,
        public readonly string $valueObjectClassname,
    ) {
        parent::__construct($message);
    }

    /**
     * @param class-string $valueObjectClassname
     */
    public static function create(int $maximumLength, string $value, string $valueObjectClassname): self
    {
        $valueLength = mb_strlen($value);

        return new self(sprintf(
            'Maximum value length for a value object of the type %s is %d. Value "%s" (length: %d) passed.',
            $valueObjectClassname,
            $maximumLength,
            $value,
            $valueLength,
        ), $value, $maximumLength, $valueLength, $valueObjectClassname);
    }
}
