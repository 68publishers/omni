<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Bus;

use SixtyEightPublishers\ArchitectureBundle\Command\CommandInterface;
use Symfony\Component\Messenger\Stamp\StampInterface;

interface CommandBusInterface
{
    /**
     * @param StampInterface[] $stamps
     */
    public function dispatch(CommandInterface $message, array $stamps = []): void;
}
