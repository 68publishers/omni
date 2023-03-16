<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\Infrastructure;

use SixtyEightPublishers\ArchitectureBundle\Bus\QueryBusInterface;
use SixtyEightPublishers\UserBundle\Domain\EmailAddressGuardInterface;
use SixtyEightPublishers\UserBundle\Domain\Exception\EmailAddressUniquenessException;
use SixtyEightPublishers\UserBundle\Domain\ValueObject\EmailAddress;
use SixtyEightPublishers\UserBundle\Domain\ValueObject\UserId;
use SixtyEightPublishers\UserBundle\ReadModel\Query\GetUserIdByEmailAddressQuery;

final class UniqueEmailAddressGuard implements EmailAddressGuardInterface
{
    public function __construct(
        private readonly QueryBusInterface $queryBus,
    ) {}

    public function __invoke(UserId $userId, EmailAddress $emailAddress): void
    {
        $foundId = $this->queryBus->dispatch(new GetUserIdByEmailAddressQuery($emailAddress->toNative()));

        if ($foundId instanceof UserId && !$foundId->equals($userId)) {
            throw EmailAddressUniquenessException::create($emailAddress->toNative());
        }
    }
}
