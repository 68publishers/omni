<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\ReadModel\View;

use JsonSerializable;

interface ViewInterface extends JsonSerializable
{
	/**
	 * @param \SixtyEightPublishers\ArchitectureBundle\ReadModel\View\ViewDataInterface $viewData
	 *
	 * @return static
	 */
	public static function fromData(ViewDataInterface $viewData): self;

	/**
	 * @return \SixtyEightPublishers\ArchitectureBundle\ReadModel\View\ViewDataInterface
	 */
	public function viewData(): ViewDataInterface;
}
