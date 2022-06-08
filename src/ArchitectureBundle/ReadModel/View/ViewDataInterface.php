<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\ReadModel\View;

interface ViewDataInterface
{
	/**
	 * @param string $key
	 * @param mixed  $value
	 *
	 * @return $this
	 */
	public function with(string $key, $value): self;

	/**
	 * @param string $key
	 * @param string ...$keys
	 *
	 * @return $this
	 */
	public function without(string $key, string ...$keys): self;

	/**
	 * @param string $key
	 *
	 * @return bool
	 */
	public function has(string $key): bool;

	/**
	 * @param string $key
	 *
	 * @return mixed
	 */
	public function get(string $key);

	/**
	 * @return array
	 */
	public function toArray(): array;
}
