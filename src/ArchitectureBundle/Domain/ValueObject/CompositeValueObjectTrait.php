<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject;

use SixtyEightPublishers\ArchitectureBundle\Domain\Exception\InvalidNativeValueTypeException;
use function array_key_exists;
use function assert;
use function get_object_vars;
use function is_array;
use function is_subclass_of;

trait CompositeValueObjectTrait
{
    public static function fromNative(mixed $native): static
    {
        if (!is_array($native)) {
            throw InvalidNativeValueTypeException::fromNativeValue($native, 'array', static::class);
        }

        $factory = static function (string $classname, string $key, bool $canBeNull = false) use ($native): ?ValueObjectInterface {
            if (!array_key_exists($key, $native)) {
                /** @var class-string $classname */
                throw InvalidNativeValueTypeException::fromNativeValue(null, 'missing', $classname);
            }

            assert(is_subclass_of($classname, ValueObjectInterface::class, true));
            $native = $native[$key];

            return null === $native && $canBeNull ? null : $classname::fromNative($native);
        };

        return self::fromNativeFactory($factory);
    }

    /**
     * @return array<string, mixed>
     */
    public function toNative(): array
    {
        return array_map(static fn (ValueObjectInterface $valueObject): mixed => $valueObject->toNative(), $this->propertiesToArray());
    }

    public function equals(ValueObjectInterface $object): bool
    {
        return $object instanceof static && DeepValueComparator::compare($this->toNative(), $object->toNative());
    }

    /**
     * @return array<string, ValueObjectInterface>
     */
    private function propertiesToArray(): array
    {
        return get_object_vars($this);
    }

    abstract protected static function fromNativeFactory(callable $factory): static;
}
