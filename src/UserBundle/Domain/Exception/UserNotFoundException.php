<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\Domain\Exception;

use DomainException;
use SixtyEightPublishers\UserBundle\Domain\Dto\UserId;

final class UserNotFoundException extends DomainException
{
	/**
	 * @param \SixtyEightPublishers\UserBundle\Domain\Dto\UserId $id
	 *
	 * @return static
	 */
	public static function withId(UserId $id): self
	{
		return new self(sprintf(
			'User with ID %s not found.',
			$id
		));
	}
}
