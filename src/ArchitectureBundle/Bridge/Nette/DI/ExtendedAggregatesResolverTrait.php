<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Bridge\Nette\DI;

trait ExtendedAggregatesResolverTrait
{
	/**
	 * [Original classname => Used classname]
	 *
	 * @return array
	 */
	abstract public function resolveExtendedAggregates(): array;
}
