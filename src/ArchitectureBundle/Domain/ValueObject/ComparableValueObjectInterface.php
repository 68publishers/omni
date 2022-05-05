<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject;

interface ComparableValueObjectInterface
{
	/**
	 * @param \SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\ComparableValueObjectInterface $valueObject
	 *
	 * @return bool
	 */
	public function equals(self $valueObject): bool;
}
