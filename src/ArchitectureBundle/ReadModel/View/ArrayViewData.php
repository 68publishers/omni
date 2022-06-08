<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\ReadModel\View;

class ArrayViewData implements ViewDataInterface
{
	private array $data;

	/**
	 * @param array $data
	 */
	public function __construct(array $data)
	{
		$this->data = $data;
	}

	/**
	 * @param array $data
	 *
	 * @return static
	 */
	public static function create(array $data): self
	{
		return new static($data);
	}

	/**
	 * {@inheritDoc}
	 */
	public function with(string $key, $value): ViewDataInterface
	{
		$data = $this->data;
		$data[$key] = $value;

		return new static($data);
	}

	/**
	 * {@inheritDoc}
	 */
	public function without(string $key, string ...$keys): ViewDataInterface
	{
		$data = $this->data;
		$keys[] = $key;

		foreach ($keys as $k) {
			if (array_key_exists($k, $data)) {
				unset($data[$k]);
			}
		}

		return new static($data);
	}

	/**
	 * {@inheritDoc}
	 */
	public function has(string $key): bool
	{
		return array_key_exists($key, $this->data);
	}

	/**
	 * {@inheritDoc}
	 */
	public function get(string $key)
	{
		return $this->data[$key] ?? NULL;
	}

	/**
	 * {@inheritDoc}
	 */
	public function toArray(): array
	{
		return $this->data;
	}
}
