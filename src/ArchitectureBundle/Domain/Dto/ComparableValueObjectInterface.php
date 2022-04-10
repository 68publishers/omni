<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Domain\Dto;

interface ComparableValueObjectInterface
{
	/**
	 * @param \SixtyEightPublishers\ArchitectureBundle\Domain\Dto\ComparableValueObjectInterface $valueObject
	 *
	 * @return bool
	 */
	public function equals(self $valueObject): bool;
}
