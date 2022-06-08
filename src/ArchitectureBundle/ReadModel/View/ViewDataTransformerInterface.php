<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\ReadModel\View;

interface ViewDataTransformerInterface
{
	/**
	 * @param string                                                                    $viewClassname
	 * @param \SixtyEightPublishers\ArchitectureBundle\ReadModel\View\ViewDataInterface $viewData
	 *
	 * @return bool
	 */
	public function canTransform(string $viewClassname, ViewDataInterface $viewData): bool;

	/**
	 * @param \SixtyEightPublishers\ArchitectureBundle\ReadModel\View\ViewDataInterface $viewData
	 *
	 * @return \SixtyEightPublishers\ArchitectureBundle\ReadModel\View\ViewDataInterface
	 */
	public function transform(ViewDataInterface $viewData): ViewDataInterface;
}
