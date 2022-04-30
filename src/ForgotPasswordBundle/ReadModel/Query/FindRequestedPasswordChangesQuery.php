<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ForgotPasswordBundle\ReadModel\Query;

use SixtyEightPublishers\ArchitectureBundle\ReadModel\Query\AbstractBatchedQuery;

/**
 * Yields batches with views of type PasswordRequestView
 */
final class FindRequestedPasswordChangesQuery extends AbstractBatchedQuery
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
