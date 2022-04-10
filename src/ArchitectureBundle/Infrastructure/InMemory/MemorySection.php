<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Infrastructure\InMemory;

final class MemorySection implements MemorySectionInterface
{
	private string $name;

	/** @var object[]  */
	private array $storage = [];

	/**
	 * @param string $name
	 */
	public function __construct(string $name)
	{
		$this->name = $name;
	}

	/**
	 * {@inheritDoc}
	 */
	public function name(): string
	{
		return $this->name;
	}

	/**
	 * {@inheritDoc}
	 */
	public function has(string $id): bool
	{
		return isset($this->storage[$id]);
	}

	/**
	 * {@inheritDoc}
	 */
	public function get(string $id): ?object
	{
		return $this->storage[$id] ?? NULL;
	}

	/**
	 * {@inheritDoc}
	 */
	public function add(string $id, object $object): void
	{
		$this->storage[$id] = $object;
	}

	/**
	 * {@inheritDoc}
	 */
	public function remove(string $id): void
	{
		if ($this->has($id)) {
			unset($this->storage[$id]);
		}
	}

	/**
	 * {@inheritDoc}
	 */
	public function filter(callable $callback): array
	{
		return array_values(array_filter($this->storage, $callback));
	}

	/**
	 * {@inheritDoc}
	 */
	public function filterOne(callable $callback): ?object
	{
		foreach ($this->storage as $item) {
			if ($callback($item)) {
				return $item;
			}
		}

		return NULL;
	}

	/**
	 * {@inheritDoc}
	 */
	public function all(): array
	{
		return array_values($this->storage);
	}
}
