<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Domain\Dto;

interface IntegerValueObjectInterface
{
	/**
	 * @param int $value
	 *
	 * @return static
	 */
	public static function fromValue(int $value): self;

	/**
	 * @return int
	 */
	public function value(): int;
}
