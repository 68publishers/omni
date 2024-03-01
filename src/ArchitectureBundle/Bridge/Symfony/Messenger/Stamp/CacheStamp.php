<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Bridge\Symfony\Messenger\Stamp;

use SixtyEightPublishers\ArchitectureBundle\Infrastructure\Cache\CacheMetadata;
use Symfony\Component\Messenger\Stamp\StampInterface;

final class CacheStamp implements StampInterface
{
    public function __construct(
        public readonly CacheMetadata $metadata,
    ) {}
}
