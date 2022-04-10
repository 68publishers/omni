<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\Domain\Dto;

use SixtyEightPublishers\ArchitectureBundle\Domain\Dto\AbstractValueObjectSet;

final class Roles extends AbstractValueObjectSet
{
	public const ITEM_CLASSNAME = Role::class;

	/**
	 * @param mixed $value
	 *
	 * @return \SixtyEightPublishers\UserBundle\Domain\Dto\Role
	 */
	protected static function reconstituteItem($value): Role
	{
		return Role::fromValue($value);
	}

	/**
	 * @param \SixtyEightPublishers\UserBundle\Domain\Dto\Role $item
	 *
	 * @return string
	 */
	protected static function exportItem($item): string
	{
		assert($item instanceof Role);

		return $item->value();
	}
}
