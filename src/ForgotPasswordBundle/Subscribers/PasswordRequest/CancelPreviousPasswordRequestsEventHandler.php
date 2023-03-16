<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ForgotPasswordBundle\Subscribers\PasswordRequest;

use SixtyEightPublishers\ArchitectureBundle\Bus\CommandBusInterface;
use SixtyEightPublishers\ArchitectureBundle\Bus\QueryBusInterface;
use SixtyEightPublishers\ArchitectureBundle\Event\EventHandlerInterface;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\Command\CancelPasswordRequestCommand;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\Event\PasswordChangeRequested;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\ValueObject\PasswordRequestId;
use SixtyEightPublishers\ForgotPasswordBundle\ReadModel\Query\FindIdsOfRequestedPasswordChangesQuery;
use function assert;

final class CancelPreviousPasswordRequestsEventHandler implements EventHandlerInterface
{
    public function __construct(
        private readonly QueryBusInterface $queryBus,
        private readonly CommandBusInterface $commandBus,
    ) {}

    public function __invoke(PasswordChangeRequested $event): void
    {
        $currentId = PasswordRequestId::fromUuid($event->getAggregateId()->toUuid());

        foreach ($this->queryBus->dispatch(new FindIdsOfRequestedPasswordChangesQuery($event->getEmailAddress()->toNative())) as $passwordRequestId) {
            assert($passwordRequestId instanceof PasswordRequestId);

            if ($currentId->equals($passwordRequestId)) {
                continue;
            }

            $deviceInfo = $event->getRequestDeviceInfo();

            $this->commandBus->dispatch(new CancelPasswordRequestCommand(
                $passwordRequestId->toNative(),
                $deviceInfo->getIpAddress()->toNative(),
                $deviceInfo->getUserAgent()->toNative(),
            ));
        }
    }
}
