<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ForgotPasswordBundle\Subscribers\PasswordRequest;

use SixtyEightPublishers\ArchitectureBundle\Bus\CommandBusInterface;
use SixtyEightPublishers\ArchitectureBundle\Bus\QueryBusInterface;
use SixtyEightPublishers\ArchitectureBundle\Event\EventHandlerInterface;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\Event\PasswordChangeCompleted;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\Exception\EmailAddressNotFoundException;
use SixtyEightPublishers\UserBundle\Domain\Command\UpdateUserCommand;
use SixtyEightPublishers\UserBundle\Domain\ValueObject\UserId;
use SixtyEightPublishers\UserBundle\ReadModel\Query\GetUserIdByEmailAddressQuery;

final class ChangePasswordOnPasswordRequestCompletedEventHandler implements EventHandlerInterface
{
    public function __construct(
        private readonly QueryBusInterface $queryBus,
        private readonly CommandBusInterface $commandBus,
    ) {}

    public function __invoke(PasswordChangeCompleted $event): void
    {
        $userId = $this->queryBus->dispatch(new GetUserIdByEmailAddressQuery($event->getEmailAddress()->toNative()));

        if (!$userId instanceof UserId) {
            throw EmailAddressNotFoundException::create($event->getEmailAddress()->toNative());
        }

        $command = new UpdateUserCommand(
            userId: $userId->toNative(),
            password: $event->getPassword(),
        );

        $this->commandBus->dispatch($command);
    }
}
