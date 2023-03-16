<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ForgotPasswordBundle\ReadModel\Query;

use SixtyEightPublishers\ArchitectureBundle\ReadModel\Query\QueryInterface;

/**
 * Returns array<PasswordRequestId>
 */
final class FindIdsOfRequestedPasswordChangesQuery implements QueryInterface
{
    public function __construct(
        public readonly string $emailAddress,
    ) {}
}
