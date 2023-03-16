<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\ReadModel\Query;

use SixtyEightPublishers\ArchitectureBundle\ReadModel\Query\QueryInterface;

/**
 * Returns ?UserId
 */
final class GetUserIdByUsernameQuery implements QueryInterface
{
    public function __construct(
        public readonly string $username,
    ) {}
}
