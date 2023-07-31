<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Bridge\Nette\DI;

use SixtyEightPublishers\DoctrineBridge\Bridge\Nette\DI\TargetEntity;

trait AutoRegisterDoctrineTargetEntitiesTrait
{
	use ExtendedAggregatesResolverTrait;

	/**
	 * @return TargetEntity[]
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
