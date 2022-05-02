<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\ReadModel\View;

abstract class AbstractView implements ViewInterface
{
	private function __construct()
	{
	}

	/**
	 * @param array $data
	 *
	 * @return static
	 */
	public static function fromArray(array $data): self
	{
		$view = new static();

		foreach ($data as $name => $value) {
			$view->{$name} = $value;
		}

		return $view;
	}
}
