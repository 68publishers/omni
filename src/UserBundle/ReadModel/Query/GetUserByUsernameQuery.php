<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\ReadModel\Query;

use SixtyEightPublishers\ArchitectureBundle\ReadModel\Query\AbstractQuery;

/**
 * Returns UserView
 */
final class GetUserByUsernameQuery extends AbstractQuery
{
	/**
	 * @param string $username
	 *
	 * @return static
	 */
	public static function create(string $username): self
	{
		return self::fromParameters([
			'username' => $username,
		]);
	}

	/**
	 * @return string
	 */
	public function username(): string
	{
		return $this->getParam('username');
	}
}
