<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject;

use SixtyEightPublishers\ArchitectureBundle\Domain\Exception\InvalidNativeValueTypeException;
use SixtyEightPublishers\ArchitectureBundle\Domain\Exception\ValueObjectSetException;
use function array_merge;
use function assert;
use function count;
use function is_array;
use function is_subclass_of;
use function sprintf;

trait AppendOnlyValueObjectSetTrait
{
    /** @var class-string<ValueObjectInterface>|null */
    private static ?string $validItemClassname = null;

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
                sprintf('array<%s>', $itemClassname),
                static::class,
            );
        }

        $items = [];

        foreach ($native as $item) {
            $items[] = ([$itemClassname, 'fromNative'])($item);
        }

        return new static($items);
    }

    public static function fromSafeNative(mixed $native): static
    {
        $itemClassname = self::getValidItemClassname();
        assert(is_array($native));

        $items = [];

        foreach ($native as $item) {
            $items[] = ([$itemClassname, 'fromSafeNative'])($item);
        }

        return new static($items);
    }

    /**
     * @param array<ValueObjectInterface> $items
     */
    public static function fromItems(array $items): static
    {
        foreach ($items as $item) {
            self::validateItem($item);
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

        foreach ($object->all() as $index => $item) {
            if (!isset($this->items[$index]) || !$this->items[$index]->equals($item)) {
                return false;
            }
        }

        return true;
    }

    public function append(ValueObjectInterface $item): static
    {
        self::validateItem($item);

        $items = $this->all();
        $items[] = $item;

        return new static($items);
    }

    /**
     * @param array<int, ValueObjectInterface> $items
     */
    public function appendAll(array $items): static
    {
        $allItems = $this->all();

        foreach ($items as $item) {
            self::validateItem($item);

            $allItems[] = $item;
        }

        return new static($allItems);
    }

    public function merge(self $valueObject): static
    {
        return new static(array_merge(
            $this->all(),
            $valueObject->all(),
        ));
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
        if (null !== self::$validItemClassname) {
            return self::$validItemClassname;
        }

        /** @var class-string $itemClassname */
        $itemClassname = static::getItemClassname();

        if (empty($itemClassname)) {
            throw ValueObjectSetException::undeclaredItemType(static::class);
        }

        if (!is_subclass_of($itemClassname, ValueObjectInterface::class, true)) {
            throw ValueObjectSetException::declaredItemTypeMustBeValueObjectImplementor(static::class, $itemClassname);
        }

        return self::$validItemClassname = $itemClassname;
    }

    private static function validateItem(ValueObjectInterface $item): void
    {
        $itemClassname = self::getValidItemClassname();

        if (!$item instanceof $itemClassname) {
            throw ValueObjectSetException::invalidItemPassed(static::class, $itemClassname, $item);
        }
    }
}
