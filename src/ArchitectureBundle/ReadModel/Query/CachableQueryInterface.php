<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\ReadModel\Query;

use SixtyEightPublishers\ArchitectureBundle\Infrastructure\Cache\CacheMetadata;

interface CachableQueryInterface extends QueryInterface
{
    public function createCacheMetadata(): CacheMetadata;
}
