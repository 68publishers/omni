<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Domain\Exception;

use DomainException;
use function get_class;
use function gettype;
use function is_object;
use function sprintf;

final class InvalidNativeValueTypeException extends DomainException
{
    /**
     * @param class-string $valueObjectClassname
     */
    public function __construct(
        string $message,
        public readonly string $expectedType,
        public readonly string $passedType,
        public readonly string $valueObjectClassname,
    ) {
        parent::__construct($message);
    }

    /**
     * @param class-string $valueObjectClassname
     */
    public static function fromNativeValue(mixed $passedNativeValue, string $expectedType, string $valueObjectClassname): self
    {
        $passedType = is_object($passedNativeValue) ? ('instance of ' . get_class($passedNativeValue)) : gettype($passedNativeValue);
        $passedType = ['boolean' => 'bool', 'integer' => 'int', 'double' => 'float', 'NULL' => 'null'][$passedType] ?? $passedType;

        return new self(sprintf(
            'Cannot instantiate an value object of the type %s. Expected native value type is %s, %s passed.',
            $valueObjectClassname,
            $expectedType,
            $passedType,
        ), $expectedType, $passedType, $valueObjectClassname);
    }
}
