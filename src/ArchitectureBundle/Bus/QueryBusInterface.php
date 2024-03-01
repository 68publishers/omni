<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Bus;

use SixtyEightPublishers\ArchitectureBundle\ReadModel\Query\QueryInterface;
use Symfony\Component\Messenger\Stamp\StampInterface;

interface QueryBusInterface
{
    /**
     * @param array<int, StampInterface> $stamps
     */
    public function dispatch(QueryInterface $message, array $stamps = []): mixed;
}
