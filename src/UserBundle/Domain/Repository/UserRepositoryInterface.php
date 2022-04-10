<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\Domain\Repository;

use SixtyEightPublishers\UserBundle\Domain\Dto\UserId;
use SixtyEightPublishers\UserBundle\Domain\Aggregate\User;

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
	 * @param \SixtyEightPublishers\UserBundle\Domain\Dto\UserId $id
	 *
	 * @return \SixtyEightPublishers\UserBundle\Domain\Aggregate\User
	 * @throws \SixtyEightPublishers\UserBundle\Domain\Exception\UserNotFoundException
	 */
	public function get(UserId $id): User;
}
