<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Infrastructure\InMemory;

interface MemorySectionInterface
{
	/**
	 * @return string
	 */
	public function name(): string;

	/**
	 * @param string $id
	 *
	 * @return bool
	 */
	public function has(string $id): bool;

	/**
	 * @param string $id
	 *
	 * @return object|NULL
	 */
	public function get(string $id): ?object;

	/**
	 * @param string $id
	 * @param object $object
	 *
	 * @return void
	 */
	public function add(string $id, object $object): void;

	/**
	 * @param string $id
	 *
	 * @return void
	 */
	public function remove(string $id): void;

	/**
	 * @param callable $callback
	 *
	 * @return array
	 */
	public function filter(callable $callback): array;

	/**
	 * @param callable $callback
	 *
	 * @return object|NULL
	 */
	public function filterOne(callable $callback): ?object;

	/**
	 * @return array
	 */
	public function all(): array;
}
