<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Bus;

use SixtyEightPublishers\ArchitectureBundle\Event\EventInterface;
use Symfony\Component\Messenger\Stamp\StampInterface;

interface EventBusInterface
{
    /**
     * @param StampInterface[] $stamps
     */
    public function dispatch(EventInterface $message, array $stamps = []): void;
}
