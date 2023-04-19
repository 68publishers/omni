<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Domain\Exception;

use DomainException;
use function sprintf;

final class MinimumValueExceededException extends DomainException
{
    /**
     * @param class-string $valueObjectClassname
     */
    public function __construct(
        string $message,
        public readonly int|float $value,
        public readonly int|float $minimum,
        public readonly string $valueObjectClassname,
    ) {
        parent::__construct($message);
    }

    /**
     * @param class-string $valueObjectClassname
     */
    public static function create(int|float $minimum, int|float $value, string $valueObjectClassname): self
    {
        return new self(sprintf(
            'Minimum value for a value object of the type %s is %s. Value %d passed.',
            $valueObjectClassname,
            $minimum,
            $value,
        ), $value, $minimum, $valueObjectClassname);
    }
}
