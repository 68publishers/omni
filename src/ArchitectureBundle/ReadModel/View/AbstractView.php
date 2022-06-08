<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\ReadModel\View;

use ReflectionObject;
use ReflectionProperty;

abstract class AbstractView implements ViewInterface
{
	private ViewDataInterface $viewData;

	private function __construct()
	{
	}

	/**
	 * {@inheritDoc}
	 */
	public static function fromData(ViewDataInterface $viewData): self
	{
		$view = new static();
		$view->viewData = $viewData;
		$reflection = new ReflectionObject($view);

		foreach ($reflection->getProperties(ReflectionProperty::IS_PUBLIC) as $property) {
			if ($viewData->has($property->getName())) {
				$property->setValue($view, $viewData->get($property->getName()));
			}
		}

		return $view;
	}

	/**
	 * {@inheritDoc}
	 */
	public function viewData(): ViewDataInterface
	{
		return $this->viewData;
	}
}
