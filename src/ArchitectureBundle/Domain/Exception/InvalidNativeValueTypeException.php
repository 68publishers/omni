<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Domain\Exception;

use DomainException;
use Throwable;
use function sprintf;

final class InvalidNativeValueTypeException extends DomainException
{
    /**
     * @param class-string $valueObjectClassname
     */
    public function __construct(
        string $message,
        public readonly string $expectedType,
        public readonly Typehint $passedType,
        public readonly string $valueObjectClassname,
        ?Throwable $previous = null,
    ) {
        parent::__construct(
            message: $message,
            previous: $previous,
        );
    }

    /**
     * @param class-string $valueObjectClassname
     */
    public static function fromNativeValue(
        mixed $passedNativeValue,
        string|Typehint $expectedType,
        string $valueObjectClassname,
        ?Throwable $previous = null,
    ): self {
        $passedType = $passedNativeValue instanceof Typehint ? $passedNativeValue : Typehint::fromVariable($passedNativeValue);
        $expectedType = (string) $expectedType;

        return new self(
            message: sprintf(
                'Cannot instantiate an value object of the type %s. Expected native value type is %s, %s passed.',
                $valueObjectClassname,
                $expectedType,
                $passedType->isInstance ? ('instance of ' . $passedType) : $passedType,
            ),
            expectedType: $expectedType,
            passedType: $passedType,
            valueObjectClassname: $valueObjectClassname,
            previous: $previous,
        );
    }
}
