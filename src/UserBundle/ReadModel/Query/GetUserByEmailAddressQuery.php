<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\ReadModel\Query;

use SixtyEightPublishers\ArchitectureBundle\ReadModel\Query\AbstractQuery;

/**
 * Returns UserView
 */
final class GetUserByEmailAddressQuery extends AbstractQuery
{
	/**
	 * @param string $emailAddress
	 *
	 * @return static
	 */
	public static function create(string $emailAddress): self
	{
		return self::fromParameters([
			'email_address' => $emailAddress,
		]);
	}

	/**
	 * @return string
	 */
	public function emailAddress(): string
	{
		return $this->getParam('email_address');
	}
}
