<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\Infrastructure;

use SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\AggregateId;
use SixtyEightPublishers\ArchitectureBundle\Infrastructure\Common\Repository\AggregateRootRepositoryInterface;
use SixtyEightPublishers\UserBundle\Domain\Exception\UserNotFoundException;
use SixtyEightPublishers\UserBundle\Domain\User;
use SixtyEightPublishers\UserBundle\Domain\UserRepositoryInterface;
use SixtyEightPublishers\UserBundle\Domain\ValueObject\UserId;

final class UserRepository implements UserRepositoryInterface
{
    public function __construct(
        private readonly AggregateRootRepositoryInterface $aggregateRootRepository,
    ) {}

    public function save(User $user): void
    {
        $this->aggregateRootRepository->saveAggregateRoot($user);
    }

    public function get(UserId $id): User
    {
        $user = $this->aggregateRootRepository->loadAggregateRoot(User::class, AggregateId::fromUuid($id->toUuid()));

        if (!$user instanceof User) {
            throw UserNotFoundException::withId($id);
        }

        return $user;
    }
}
