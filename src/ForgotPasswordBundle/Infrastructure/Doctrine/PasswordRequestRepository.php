<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ForgotPasswordBundle\Infrastructure\Doctrine;

use SixtyEightPublishers\ArchitectureBundle\Infrastructure\Doctrine\Repository\DoctrineAggregateRootRepositoryInterface;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\Exception\PasswordRequestNotFoundException;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\PasswordRequest;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\PasswordRequestRepositoryInterface;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\ValueObject\PasswordRequestId;

final class PasswordRequestRepository implements PasswordRequestRepositoryInterface
{
    public function __construct(
        private readonly DoctrineAggregateRootRepositoryInterface $aggregateRootRepository,
    ) {}

    public function save(PasswordRequest $passwordRequest): void
    {
        $this->aggregateRootRepository->saveAggregateRoot($passwordRequest);
    }

    public function get(PasswordRequestId $id): PasswordRequest
    {
        $passwordRequest = $this->aggregateRootRepository->loadAggregateRoot(PasswordRequest::class, $id);

        if (!$passwordRequest instanceof PasswordRequest) {
            throw PasswordRequestNotFoundException::withId($id);
        }

        return $passwordRequest;
    }
}
