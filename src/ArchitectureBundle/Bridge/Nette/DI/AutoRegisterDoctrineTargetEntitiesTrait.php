<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Bridge\Nette\DI;

use SixtyEightPublishers\DoctrineBridge\DI\TargetEntity;

trait AutoRegisterDoctrineTargetEntitiesTrait
{
	use ExtendedAggregatesResolverTrait;

	/**
	 * @return \SixtyEightPublishers\DoctrineBridge\DI\TargetEntity[]
	 */
	public function getTargetEntities(): array
	{
		$targetEntities = [];

		foreach ($this->resolveExtendedAggregates() as $originalClassname => $usedClassname) {
			if ($originalClassname !== $usedClassname) {
				$targetEntities[] = new TargetEntity($originalClassname, $usedClassname);
			}
		}

		return $targetEntities;
	}
}
