<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Domain\Dto;

use Countable;

interface ValueObjectSetInterface extends ComparableValueObjectInterface, Countable
{
	/**
	 * @return static
	 */
	public static function empty(): self;

	/**
	 * @param object[] $items
	 *
	 * @return static
	 */
	public static function fromItems(array $items): self;

	/**
	 * @param array $items
	 *
	 * @return static
	 */
	public static function reconstitute(array $items): self;

	/**
	 * @param \SixtyEightPublishers\ArchitectureBundle\Domain\Dto\ComparableValueObjectInterface $item
	 *
	 * @return $this
	 */
	public function with(ComparableValueObjectInterface $item): self;

	/**
	 * @param \SixtyEightPublishers\ArchitectureBundle\Domain\Dto\ComparableValueObjectInterface $item
	 *
	 * @return $this
	 */
	public function without(ComparableValueObjectInterface $item): self;

	/**
	 * @param \SixtyEightPublishers\ArchitectureBundle\Domain\Dto\ComparableValueObjectInterface $item
	 *
	 * @return bool
	 */
	public function has(ComparableValueObjectInterface $item): bool;

	/**
	 * @return array
	 */
	public function all(): array;

	/**
	 * @return array
	 */
	public function toArray(): array;
}
