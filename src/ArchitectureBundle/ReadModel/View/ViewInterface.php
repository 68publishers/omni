<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\ReadModel\View;

use JsonSerializable;

interface ViewInterface extends JsonSerializable
{
	/**
	 * @param array $data
	 *
	 * @return static
	 */
	public static function fromArray(array $data): self;
}
