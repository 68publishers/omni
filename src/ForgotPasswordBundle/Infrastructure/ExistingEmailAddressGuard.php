<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ForgotPasswordBundle\Infrastructure;

use SixtyEightPublishers\ArchitectureBundle\Bus\QueryBusInterface;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\EmailAddressGuardInterface;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\Exception\EmailAddressNotFoundException;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\ValueObject\EmailAddress;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\ValueObject\PasswordRequestId;
use SixtyEightPublishers\UserBundle\ReadModel\Query\GetUserIdByEmailAddressQuery;

final class ExistingEmailAddressGuard implements EmailAddressGuardInterface
{
    public function __construct(
        private readonly QueryBusInterface $queryBus,
    ) {}

    public function __invoke(PasswordRequestId $passwordRequestId, EmailAddress $emailAddress): void
    {
        if (null === $this->queryBus->dispatch(new GetUserIdByEmailAddressQuery($emailAddress->toNative()))) {
            throw EmailAddressNotFoundException::create($emailAddress->toNative());
        }
    }
}
