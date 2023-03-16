<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject;

use RuntimeException;
use SixtyEightPublishers\ArchitectureBundle\Domain\Exception\InvalidNativeValueTypeException;
use function array_key_exists;
use function array_merge;
use function count;
use function get_class;
use function is_array;
use function sprintf;

trait ArrayValueTrait
{
    /**
     * @param array<mixed> $values
     */
    protected function __construct(
        protected readonly array $values,
    ) {
    }

    public static function fromNative(mixed $native): static
    {
        if (!is_array($native)) {
            throw InvalidNativeValueTypeException::fromNativeValue($native, 'array', static::class);
        }

        return new static($native);
    }

    /**
     * @return array<mixed>
     */
    public function toNative(): array
    {
        return $this->all();
    }

    public function equals(ValueObjectInterface $object): bool
    {
        if (!$object instanceof static) {
            return false;
        }

        return DeepValueComparator::compare($this->toNative(), $object->toNative());
    }

    public function merge(ValueObjectInterface $object): static
    {
        if (!$object instanceof static) {
            throw new RuntimeException(sprintf(
                'Value object of the type %s is not compatible with %s.',
                get_class($object),
                static::class,
            ));
        }

        return new static(array_merge($this->toNative(), $object->toNative()));
    }

    public function with(string $name, mixed $value): static
    {
        $native = $this->toNative();
        $native[$name] = $value;

        return new static($native);
    }

    public function without(string $name): static
    {
        $native = $this->toNative();

        if (array_key_exists($name, $native)) {
            unset($native[$name]);
        }

        return new static($native);
    }

    /**
     * @return array<mixed>
     */
    public function all(): array
    {
        return $this->values;
    }

    public function get(string $name): mixed
    {
        return $this->values[$name] ?? null;
    }

    public function has(string $name): bool
    {
        return isset($this->values[$name]);
    }

    public function count(): int
    {
        return count($this->all());
    }
}
