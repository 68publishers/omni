<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ForgotPasswordBundle\ReadModel\Query;

use SixtyEightPublishers\ArchitectureBundle\ReadModel\Query\QueryInterface;

/**
 * Returns boolean
 */
final class IsPossibleToCompletePasswordChangeRequestQuery implements QueryInterface
{
    public function __construct(
        public readonly string $passwordRequestId,
    ) {}
}
