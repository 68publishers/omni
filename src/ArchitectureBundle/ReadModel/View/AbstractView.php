<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\ReadModel\View;

use LogicException;

abstract class AbstractView implements ViewInterface
{
	private array $data;

	private function __construct()
	{
	}

	/**
	 * @param array $data
	 *
	 * @return static
	 */
	public static function fromArray(array $data): self
	{
		$view = new static();
		$view->data = $data;

		return $view;
	}

	/**
	 * {@inheritDoc}
	 */
	public function has(string $field): bool
	{
		return array_key_exists($field, $this->data);
	}

	/**
	 * {@inheritDoc}
	 */
	public function get(string $field)
	{
		return $this->data[$field] ?? NULL;
	}

	/**
	 * @param string $name
	 *
	 * @return mixed|NULL
	 */
	public function __get(string $name)
	{
		return $this->get($name);
	}

	/**
	 * @param string $name
	 * @param $value
	 *
	 * @return void
	 * @throws \LogicException
	 */
	public function __set(string $name, $value): void
	{
		throw new LogicException(sprintf(
			'Can\'t set property %s::$%s, views are readonly.',
			static::class,
			$name
		));
	}

	/**
	 * @param string $name
	 *
	 * @return bool
	 */
	public function __isset(string $name): bool
	{
		return $this->has($name);
	}
}
