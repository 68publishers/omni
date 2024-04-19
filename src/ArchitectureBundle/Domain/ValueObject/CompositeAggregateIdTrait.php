<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject;

use DomainException;
use SixtyEightPublishers\ArchitectureBundle\Domain\Exception\InvalidNativeValueTypeException;
use SixtyEightPublishers\ArchitectureBundle\Domain\Exception\Typehint;
use function array_key_exists;
use function count;
use function implode;

trait CompositeAggregateIdTrait
{
    /**
     * @param non-empty-array<string, ValueObjectInterface> $values
     */
    protected function __construct(
        protected readonly array $values,
    ) {
    }

    /**
     * @return non-empty-array<string, class-string<ValueObjectInterface>>
     */
    abstract public static function getStructure(): array;

    public static function fromNative(mixed $native): static
    {
        if (!\is_array($native)) {
            throw InvalidNativeValueTypeException::fromNativeValue(
                passedNativeValue: $native,
                expectedType: self::createTypehint(),
                valueObjectClassname: static::class,
            );
        }

        $values = [];

        foreach (static::getStructure() as $key => $valueObjectClassname) {
            if (!array_key_exists($key, $native)) {
                throw InvalidNativeValueTypeException::fromNativeValue(
                    passedNativeValue: $native,
                    expectedType: self::createTypehint(),
                    valueObjectClassname: static::class,
                );
            }

            $values[$key] = $valueObjectClassname::fromNative($native[$key]);
        }

        return new static(
            values: $values,
        );
    }

    public static function fromSafeNative(mixed $native): static
    {
        assert(is_array($native));

        $values = [];

        foreach (static::getStructure() as $key => $valueObjectClassname) {
            \assert(array_key_exists($key, $native));

            $values[$key] = $valueObjectClassname::fromSafeNative($native[$key]);
        }

        return new static(
            values: $values,
        );
    }

    public static function isValid(mixed $native): bool
    {
        if (\is_array($native)) {
            return false;
        }

        foreach (static::getStructure() as $key => $valueObjectClassname) {
            if (!array_key_exists($key, $native)) {
                return false;
            }

            try {
                $valueObjectClassname::fromNative($native[$key]);
            } catch (DomainException $e) {
                return false;
            }

            unset($native[$key]);
        }

        if (0 < count($native)) {
            return false;
        }

        return true;
    }

    /**
     * @return non-empty-array<string, mixed>
     */
    public function toNative(): array
    {
        $native = [];

        foreach ($this->values as $key => $value) {
            $native[$key] = $value->toNative();
        }

        return $native;
    }

    public function equals(ValueObjectInterface $object): bool
    {
        if (!($object instanceof static)) {
            return false;
        }

        $leftValues = $this->getValues();
        $rightValues = $this->getValues();

        if (count($leftValues) !== count($rightValues)) {
            return false;
        }

        foreach ($leftValues as $k => $leftValue) {
            if (!array_key_exists($k, $rightValues)) {
                return false;
            }

            $rightValue = $rightValues[$k];

            if (null === $leftValue && null === $rightValue) {
                continue;
            }

            if ((null === $leftValue && null !== $rightValue) || (null !== $leftValue && null === $rightValue) || !$leftValue->equals($rightValue)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @return non-empty-array<string, ValueObjectInterface>
     */
    public function getValues(): array
    {
        return $this->values;
    }

    public function toString(): string
    {
        return implode('/', $this->values);
    }

    public function __toString(): string
    {
        return $this->toString();
    }

    private static function createTypehint(): Typehint
    {
        $values = [];

        foreach (static::getStructure() as $key => $valueObjectClassname) {
            $values[] = $key . ': native-value-of<' . $valueObjectClassname . '>';
        }

        return new Typehint(
            value: 'array{' . implode(', ', $values) . '}',
            isInstance: false,
        );
    }
}
