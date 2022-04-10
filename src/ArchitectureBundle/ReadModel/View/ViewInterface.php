<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\ReadModel\View;

interface ViewInterface
{
	/**
	 * @param array $data
	 *
	 * @return static
	 */
	public static function fromArray(array $data): self;

	/**
	 * @param string $field
	 *
	 * @return bool
	 */
	public function has(string $field): bool;

	/**
	 * @param string $field
	 *
	 * @return mixed
	 */
	public function get(string $field);
}
