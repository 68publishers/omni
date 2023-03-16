<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject;

use SixtyEightPublishers\ArchitectureBundle\Domain\Exception\InvalidNativeValueTypeException;
use SixtyEightPublishers\ArchitectureBundle\Domain\Exception\ValueObjectSetException;
use function count;
use function is_array;
use function is_subclass_of;
use function sprintf;

trait ValueObjectSetTrait
{
    /**
     * @param array<ValueObjectInterface> $items
     */
    protected function __construct(
        protected readonly array $items,
    ) {
    }

    public static function empty(): static
    {
        return new static([]);
    }

    public static function fromNative(mixed $native): static
    {
        $itemClassname = self::getValidItemClassname();

        if (!is_array($native)) {
            throw InvalidNativeValueTypeException::fromNativeValue(
                $native,
                empty($itemClassname) ? 'array' : sprintf('array<%s>', $itemClassname),
                static::class,
            );
        }

        $items = [];

        foreach ($native as $item) {
            $items[] = ([$itemClassname, 'fromNative'])($item);
        }

        return new static($items);
    }

    /**
     * @param array<ValueObjectInterface> $items
     */
    public static function fromItems(array $items): static
    {
        $itemClassname = self::getValidItemClassname();

        foreach ($items as $item) {
            if (!$item instanceof $itemClassname) {
                throw ValueObjectSetException::invalidItemPassed(static::class, $itemClassname, $item);
            }
        }

        return new static($items);
    }

    /**
     * @return array<int, mixed>
     */
    public function toNative(): array
    {
        $values = [];

        foreach ($this->all() as $item) {
            $values[] = $item->toNative();
        }

        return $values;
    }

    public function equals(ValueObjectInterface $object): bool
    {
        if (!$object instanceof static || $object->count() !== $this->count()) {
            return false;
        }

        foreach ($object->all() as $item) {
            if (!$this->has($item)) {
                return false;
            }
        }

        return true;
    }

    public function with(ValueObjectInterface $item): static
    {
        $items = $this->all();

        if (!$this->has($item)) {
            $items[] = $item;
        }

        return self::fromItems($items);
    }

    public function without(ValueObjectInterface $item): static
    {
        $items = $this->all();

        foreach ($items as $index => $i) {
            if ($i->equals($item)) {
                unset($items[$index]);

                break;
            }
        }

        return new static($items);
    }

    public function has(ValueObjectInterface $item): bool
    {
        foreach ($this->all() as $i) {
            if ($i->equals($item)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return array<ValueObjectInterface>
     */
    public function all(): array
    {
        return $this->items;
    }

    public function count(): int
    {
        return count($this->all());
    }

    /**
     * @return class-string<ValueObjectInterface>
     */
    abstract protected static function getItemClassname(): string;

    /**
     * @return class-string<ValueObjectInterface>
     */
    private static function getValidItemClassname(): string
    {
        /** @var class-string $itemClassname */
        $itemClassname = static::getItemClassname();

        if (empty($itemClassname)) {
            throw ValueObjectSetException::undeclaredItemType(static::class);
        }

        if (!is_subclass_of($itemClassname, ValueObjectInterface::class, true)) {
            throw ValueObjectSetException::declaredItemTypeMustBeValueObjectImplementor(static::class, $itemClassname);
        }

        return $itemClassname;
    }
}
