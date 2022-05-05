<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\Domain;

use SixtyEightPublishers\UserBundle\Domain\ValueObject\UserId;
use SixtyEightPublishers\UserBundle\Domain\ValueObject\Username;

interface CheckUsernameUniquenessInterface
{
	/**
	 * @param \SixtyEightPublishers\UserBundle\Domain\ValueObject\UserId   $userId
	 * @param \SixtyEightPublishers\UserBundle\Domain\ValueObject\Username $username
	 *
	 * @return void
	 * @throws \SixtyEightPublishers\UserBundle\Domain\Exception\UsernameUniquenessException
	 */
	public function __invoke(UserId $userId, Username $username): void;
}
