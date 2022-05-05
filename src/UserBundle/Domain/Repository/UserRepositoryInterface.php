<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\Domain\Repository;

use SixtyEightPublishers\UserBundle\Domain\Aggregate\User;
use SixtyEightPublishers\UserBundle\Domain\ValueObject\UserId;

interface UserRepositoryInterface
{
	/**
	 * @return string|\SixtyEightPublishers\UserBundle\Domain\Aggregate\User
	 */
	public function classname(): string;

	/**
	 * @param \SixtyEightPublishers\UserBundle\Domain\Aggregate\User $user
	 *
	 * @return void
	 */
	public function save(User $user): void;

	/**
	 * @param \SixtyEightPublishers\UserBundle\Domain\ValueObject\UserId $id
	 *
	 * @return \SixtyEightPublishers\UserBundle\Domain\Aggregate\User
	 * @throws \SixtyEightPublishers\UserBundle\Domain\Exception\UserNotFoundException
	 */
	public function get(UserId $id): User;
}
