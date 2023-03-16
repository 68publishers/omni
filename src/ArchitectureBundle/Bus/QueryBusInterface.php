<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Bus;

use SixtyEightPublishers\ArchitectureBundle\ReadModel\Query\QueryInterface;

interface QueryBusInterface
{
    public function dispatch(QueryInterface $message): mixed;
}
