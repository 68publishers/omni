<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Infrastructure\Common\Exception;

use RuntimeException;
use function sprintf;

final class TransactionException extends RuntimeException
{
    public static function unableToBeginTransaction(?string $reason = null): self
    {
        return new self(sprintf(
            'Unable to begin a transaction.%s',
            !empty($reason) ? ' ' . $reason : '',
        ));
    }

    public static function unableToCommitTransaction(?string $reason = null): self
    {
        return new self(sprintf(
            'Unable to commit a transaction.%s',
            !empty($reason) ? ' ' . $reason : '',
        ));
    }

    public static function unableToRollbackTransaction(?string $reason = null): self
    {
        return new self(sprintf(
            'Unable to rollback a transaction.%s',
            !empty($reason) ? ' ' . $reason : '',
        ));
    }
}
