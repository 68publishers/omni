<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\ReadModel\View;

interface ViewFactoryInterface
{
	/**
	 * @template T
	 * @param class-string<T>                                                           $viewClassname
	 * @param \SixtyEightPublishers\ArchitectureBundle\ReadModel\View\ViewDataInterface $viewData
	 *
	 * @return T
	 */
	public function create(string $viewClassname, ViewDataInterface $viewData): ViewInterface;
}
