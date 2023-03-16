<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Bridge\Nette\DI;

trait ExtendedAggregatesResolverTrait
{
    /**
     * [Original classname => Used classname]
     *
     * @return array<class-string, class-string>
     */
    abstract public function resolveExtendedAggregates(): array;
}
