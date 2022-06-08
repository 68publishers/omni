<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\Infrastructure\Doctrine\ReadModel;

use SixtyEightPublishers\UserBundle\Domain\ValueObject\Name;
use SixtyEightPublishers\UserBundle\ReadModel\View\UserView;
use SixtyEightPublishers\ArchitectureBundle\ReadModel\View\ViewDataInterface;
use SixtyEightPublishers\ArchitectureBundle\ReadModel\View\ViewDataTransformerInterface;
use SixtyEightPublishers\ArchitectureBundle\Infrastructure\Doctrine\ReadModel\DoctrineViewData;

final class UserViewDataTransformer implements ViewDataTransformerInterface
{
	/**
	 * {@inheritDoc}
	 */
	public function canTransform(string $viewClassname, ViewDataInterface $viewData): bool
	{
		return is_a($viewClassname, UserView::class, TRUE) && $viewData instanceof DoctrineViewData;
	}

	/**
	 * {@inheritDoc}
	 */
	public function transform(ViewDataInterface $viewData): ViewDataInterface
	{
		return $viewData
			->with('name', Name::fromValues($viewData->get('name.firstname') ?? '', $viewData->get('name.surname') ?? ''))
			->without('name.firstname', 'name.surname');
	}
}
