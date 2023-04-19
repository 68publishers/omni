<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Domain\Exception;

use DomainException;
use function sprintf;

final class MaximumValueExceededException extends DomainException
{
    /**
     * @param class-string $valueObjectClassname
     */
    public function __construct(
        string $message,
        public readonly int|float $value,
        public readonly int|float $maximum,
        public readonly string $valueObjectClassname,
    ) {
        parent::__construct($message);
    }

    /**
     * @param class-string $valueObjectClassname
     */
    public static function create(int|float $maximum, int|float $value, string $valueObjectClassname): self
    {
        return new self(sprintf(
            'Maximum value for a value object of the type %s is %s. Value %d passed.',
            $valueObjectClassname,
            $maximum,
            $value,
        ), $value, $maximum, $valueObjectClassname);
    }
}
