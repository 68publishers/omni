<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\Domain;

use SixtyEightPublishers\UserBundle\Domain\Exception\UserNotFoundException;
use SixtyEightPublishers\UserBundle\Domain\ValueObject\UserId;

interface UserRepositoryInterface
{
    public function save(User $user): void;

    /**
     * @throws UserNotFoundException
     */
    public function get(UserId $id): User;
}
