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
	 * @param string $userId
	 *
	 * @return static
	 */
	public static function create(string $userId): self
	{
		return self::fromParameters([
			'user_id' => $userId,
		]);
	}

	/**
	 * @return string
	 */
	public function userId(): string
	{
		return $this->getParam('user_id');
	}
}
