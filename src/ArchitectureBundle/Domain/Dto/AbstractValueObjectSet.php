<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Domain\Dto;

use BadMethodCallException;
use SixtyEightPublishers\ArchitectureBundle\Domain\Exception\ValueObjectSetException;

abstract class AbstractValueObjectSet implements ValueObjectSetInterface
{
	protected const ITEM_CLASSNAME = '';

	/** @var \SixtyEightPublishers\ArchitectureBundle\Domain\Dto\ComparableValueObjectInterface[]  */
	protected array $items = [];

	private function __construct()
	{
	}

	/**
	 * {@inheritDoc}
	 */
	public static function empty(): ValueObjectSetInterface
	{
		return new static();
	}

	/**
	 * {@inheritDoc}
	 */
	public static function fromItems(array $items): ValueObjectSetInterface
	{
		$itemClassname = static::ITEM_CLASSNAME;

		if (!is_subclass_of($itemClassname, ComparableValueObjectInterface::class, TRUE)) {
			throw ValueObjectSetException::declaredItemTypeMustBeComparable(static::class, $itemClassname);
		}

		foreach ($items as $item) {
			if (!$item instanceof $itemClassname) {
				throw ValueObjectSetException::invalidItemTypePassed(static::class, $itemClassname, get_class($item));
			}
		}

		$set = new static();
		$set->items = $items;

		return $set;
	}

	/**
	 * {@inheritDoc}
	 */
	public static function reconstitute(array $items): ValueObjectSetInterface
	{
		return static::fromItems(
			array_map(static fn ($item) => static::reconstituteItem($item), $items)
		);
	}

	/**
	 * {@inheritDoc}
	 */
	public function with(ComparableValueObjectInterface $item): ValueObjectSetInterface
	{
		$items = $this->items;

		if (!$this->has($item)) {
			$items[] = $item;
		}

		return self::fromItems($items);
	}

	/**
	 * {@inheritDoc}
	 */
	public function without(ComparableValueObjectInterface $item): ValueObjectSetInterface
	{
		$items = $this->items;

		foreach ($items as $index => $i) {
			if ($i->equals($item)) {
				unset($items[$index]);

				break;
			}
		}

		return self::fromItems(array_values($items));
	}

	/**
	 * {@inheritDoc}
	 */
	public function has(ComparableValueObjectInterface $item): bool
	{
		foreach ($this->all() as $i) {
			if ($i->equals($item)) {
				return TRUE;
			}
		}

		return FALSE;
	}

	/**
	 * {@inheritDoc}
	 */
	public function all(): array
	{
		return $this->items;
	}

	/**
	 * {@inheritDoc}
	 */
	public function toArray(): array
	{
		$items = [];

		foreach ($this->all() as $item) {
			$items[] = static::exportItem($item);
		}

		return $items;
	}

	/**
	 * {@inheritDoc}
	 */
	public function equals(ComparableValueObjectInterface $valueObject): bool
	{
		if (!$valueObject instanceof static || $valueObject->count() !== $this->count()) {
			return FALSE;
		}

		foreach ($valueObject->all() as $item) {
			if (!$this->has($item)) {
				return FALSE;
			}
		}

		return TRUE;
	}

	/**
	 * {@inheritDoc}
	 */
	public function count(): int
	{
		return count($this->items);
	}

	/**
	 * @param $value
	 *
	 * @return mixed
	 */
	protected static function reconstituteItem($value)
	{
		throw new BadMethodCallException(sprintf(
			'Calling of method %s is not allowed, please redeclare it.',
			__METHOD__
		));
	}

	/**
	 * @param $item
	 *
	 * @return mixed
	 */
	protected static function exportItem($item)
	{
		throw new BadMethodCallException(sprintf(
			'Calling of method %s is not allowed, please redeclare it.',
			__METHOD__
		));
	}
}
