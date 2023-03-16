<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\Infrastructure;

use SixtyEightPublishers\ArchitectureBundle\Bus\QueryBusInterface;
use SixtyEightPublishers\UserBundle\Domain\Exception\UsernameUniquenessException;
use SixtyEightPublishers\UserBundle\Domain\UsernameGuardInterface;
use SixtyEightPublishers\UserBundle\Domain\ValueObject\UserId;
use SixtyEightPublishers\UserBundle\Domain\ValueObject\Username;
use SixtyEightPublishers\UserBundle\ReadModel\Query\GetUserIdByUsernameQuery;

final class UniqueUsernameGuard implements UsernameGuardInterface
{
    public function __construct(
        private readonly QueryBusInterface $queryBus,
    ) {}

    public function __invoke(UserId $userId, Username $username): void
    {
        $foundId = $this->queryBus->dispatch(new GetUserIdByUsernameQuery($username->toNative()));

        if ($foundId instanceof UserId && !$foundId->equals($userId)) {
            throw UsernameUniquenessException::create($username->toNative());
        }
    }
}
