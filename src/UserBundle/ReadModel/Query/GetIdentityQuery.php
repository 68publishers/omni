<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\ReadModel\Query;

use SixtyEightPublishers\ArchitectureBundle\ReadModel\Query\AbstractQuery;

/**
 * Returns IdentityView
 */
final class GetIdentityQuery extends AbstractQuery
{
	/**
	 * @param string $id
	 *
	 * @return static
	 */
	public static function create(string $id): self
	{
		return self::fromParameters([
			'id' => $id,
		]);
	}

	/**
	 * @return string
	 */
	public function id(): string
	{
		return $this->getParam('id');
	}
}
