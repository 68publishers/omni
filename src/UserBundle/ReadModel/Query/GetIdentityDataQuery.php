<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\ReadModel\Query;

use SixtyEightPublishers\ArchitectureBundle\ReadModel\Query\QueryInterface;

/**
 * Returns ?IdentityData
 */
final class GetIdentityDataQuery implements QueryInterface
{
    public function __construct(
        public readonly string $userId,
    ) {}
}
