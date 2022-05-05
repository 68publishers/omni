<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\Infrastructure\Repository;

use SixtyEightPublishers\UserBundle\Domain\Aggregate\User;
use SixtyEightPublishers\UserBundle\Domain\ValueObject\UserId;
use SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\AggregateId;
use SixtyEightPublishers\UserBundle\Domain\Exception\UserNotFoundException;
use SixtyEightPublishers\UserBundle\Domain\Repository\UserRepositoryInterface;
use SixtyEightPublishers\ArchitectureBundle\Infrastructure\Common\Repository\AggregateRootRepositoryInterface;

final class UserRepository implements UserRepositoryInterface
{
	private string $classname;

	private AggregateRootRepositoryInterface $aggregateRootRepository;

	/**
	 * @param string                                                                                                     $classname
	 * @param \SixtyEightPublishers\ArchitectureBundle\Infrastructure\Common\Repository\AggregateRootRepositoryInterface $aggregateRootRepository
	 */
	public function __construct(string $classname, AggregateRootRepositoryInterface $aggregateRootRepository)
	{
		$this->classname = $classname;
		$this->aggregateRootRepository = $aggregateRootRepository;
	}

	/**
	 * {@inheritDoc}
	 */
	public function classname(): string
	{
		return $this->classname;
	}

	/**
	 * {@inheritDoc}
	 */
	public function save(User $user): void
	{
		$this->aggregateRootRepository->saveAggregateRoot($user);
	}

	/**
	 * {@inheritDoc}
	 */
	public function get(UserId $id): User
	{
		$user = $this->aggregateRootRepository->loadAggregateRoot($this->classname(), AggregateId::fromUuid($id->id()));

		if (!$user instanceof User) {
			throw UserNotFoundException::withId($id);
		}

		return $user;
	}
}
