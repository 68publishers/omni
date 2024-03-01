<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Infrastructure\Cache;

use InvalidArgumentException;
use function sprintf;

final class MissingCacheServiceException extends InvalidArgumentException
{
    public static function create(string $name): self
    {
        return new self(
            message: sprintf(
                'Cache service "%s" is missing.',
                $name,
            ),
        );
    }
}
