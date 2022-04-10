<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\Application\Exception;

use Exception;
use SixtyEightPublishers\UserBundle\Domain\Dto\UserId;

final class IdentityException extends Exception
{
	/**
	 * @param string $message
	 */
	private function __construct(string $message)
	{
		parent::__construct($message);
	}

	/**
	 * @return static
	 */
	public static function unableToRetrieveDataFromSleepingIdentity(): self
	{
		return new self('Unable to retrieve data from sleeping identity.');
	}

	/**
	 * @param \SixtyEightPublishers\UserBundle\Domain\Dto\UserId $userId
	 *
	 * @return static
	 */
	public static function dataNotFound(UserId $userId): self
	{
		return new self(sprintf(
			'Data for user %s not found.',
			$userId->toString()
		));
	}
}
