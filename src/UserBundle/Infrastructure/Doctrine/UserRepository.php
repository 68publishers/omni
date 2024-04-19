<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\Infrastructure\Doctrine;

use SixtyEightPublishers\ArchitectureBundle\Infrastructure\Doctrine\Repository\DoctrineAggregateRootRepositoryInterface;
use SixtyEightPublishers\UserBundle\Domain\Exception\UserNotFoundException;
use SixtyEightPublishers\UserBundle\Domain\User;
use SixtyEightPublishers\UserBundle\Domain\UserRepositoryInterface;
use SixtyEightPublishers\UserBundle\Domain\ValueObject\UserId;

final class UserRepository implements UserRepositoryInterface
{
    public function __construct(
        private readonly DoctrineAggregateRootRepositoryInterface $aggregateRootRepository,
    ) {}

    public function save(User $user): void
    {
        $this->aggregateRootRepository->saveAggregateRoot($user);
    }

    public function get(UserId $id): User
    {
        $user = $this->aggregateRootRepository->loadAggregateRoot(User::class, $id);

        if (!$user instanceof User) {
            throw UserNotFoundException::withId($id);
        }

        return $user;
    }
}
