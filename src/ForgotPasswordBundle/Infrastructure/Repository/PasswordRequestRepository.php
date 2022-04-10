<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ForgotPasswordBundle\Infrastructure\Repository;

use SixtyEightPublishers\ArchitectureBundle\Domain\Dto\AggregateId;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\Dto\PasswordRequestId;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\Aggregate\PasswordRequest;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\Exception\PasswordRequestNotFoundException;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\Repository\PasswordRequestRepositoryInterface;
use SixtyEightPublishers\ArchitectureBundle\Infrastructure\Common\Repository\AggregateRootRepositoryInterface;

final class PasswordRequestRepository implements PasswordRequestRepositoryInterface
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
	public function save(PasswordRequest $passwordRequest): void
	{
		$this->aggregateRootRepository->saveAggregateRoot($passwordRequest);
	}

	/**
	 * {@inheritDoc}
	 */
	public function get(PasswordRequestId $id): PasswordRequest
	{
		$passwordRequest = $this->aggregateRootRepository->loadAggregateRoot($this->classname(), AggregateId::fromUuid($id->id()));

		if (!$passwordRequest instanceof PasswordRequest) {
			throw PasswordRequestNotFoundException::withId($id);
		}

		return $passwordRequest;
	}
}
