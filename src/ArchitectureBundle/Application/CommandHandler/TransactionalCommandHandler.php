<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Application\CommandHandler;

use SixtyEightPublishers\ArchitectureBundle\Application\Command\TransactionalCommand;
use SixtyEightPublishers\ArchitectureBundle\Bus\CommandBusInterface;
use SixtyEightPublishers\ArchitectureBundle\Command\CommandHandlerInterface;

/**
 * Transactions are internally handled via StoreTransactionMiddleware
 */
final class TransactionalCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly CommandBusInterface $commandBus,
    ) {}

    public function __invoke(TransactionalCommand $command): void
    {
        foreach ($command->commands as [$command, $stamps]) {
            $this->commandBus->dispatch($command, $stamps);
        }
    }
}
