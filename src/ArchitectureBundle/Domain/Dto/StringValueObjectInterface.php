<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Domain\Dto;

interface StringValueObjectInterface
{
	/**
	 * @param string $value
	 *
	 * @return static
	 */
	public static function fromValue(string $value): self;

	/**
	 * @return string
	 */
	public function value(): string;
}
