<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Application\Command;

use SixtyEightPublishers\ArchitectureBundle\Command\CommandInterface;
use Symfony\Component\Messenger\Stamp\StampInterface;

final class TransactionalCommand implements CommandInterface
{
    /**
     * @param array<array{0: CommandInterface, 1: array<StampInterface>}> $commands
     */
    private function __construct(
        public readonly array $commands,
    ) {}

    public static function create(): self
    {
        return new self([]);
    }

    /**
     * @param array<StampInterface> $stamps
     */
    public function with(CommandInterface $command, array $stamps = []): self
    {
        $commands = $this->commands;
        $commands[] = [$command, $stamps];

        return new self($commands);
    }
}
