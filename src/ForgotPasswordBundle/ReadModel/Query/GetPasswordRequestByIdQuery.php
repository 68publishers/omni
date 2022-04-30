<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ForgotPasswordBundle\ReadModel\Query;

use SixtyEightPublishers\ArchitectureBundle\ReadModel\Query\AbstractQuery;

final class GetPasswordRequestByIdQuery extends AbstractQuery
{
	/**
	 * @param string $passwordRequestId
	 *
	 * @return static
	 */
	public static function create(string $passwordRequestId): self
	{
		return self::fromParameters([
			'id' => $passwordRequestId,
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
