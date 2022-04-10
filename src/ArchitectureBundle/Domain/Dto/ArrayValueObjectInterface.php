<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Domain\Dto;

interface ArrayValueObjectInterface extends ComparableValueObjectInterface
{
	/**
	 * @param array $array
	 *
	 * @return $this
	 */
	public function fromArray(array $array): self;

	/**
	 * @return array
	 */
	public function values(): array;

	/**
	 * @param string $name
	 *
	 * @return mixed
	 */
	public function get(string $name);

	/**
	 * @param string $name
	 *
	 * @return bool
	 */
	public function has(string $name): bool;
}
