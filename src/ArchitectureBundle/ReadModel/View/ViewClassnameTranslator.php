<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\ReadModel\View;

use InvalidArgumentException;

final class ViewClassnameTranslator implements ViewClassnameTranslatorInterface
{
	private array $classnames = [];

	/**
	 * {@inheritDoc}
	 */
	public function register(string $viewClassname, string $extendedViewClassname): void
	{
		if (!is_subclass_of($extendedViewClassname, $viewClassname, TRUE)) {
			throw new InvalidArgumentException(sprintf(
				'Class %s must be inheritor of %s.',
				$extendedViewClassname,
				$viewClassname
			));
		}

		$this->classnames[$viewClassname] = $extendedViewClassname;
	}

	/**
	 * {@inheritDoc}
	 */
	public function translate(string $viewClassname): string
	{
		return $this->classnames[$viewClassname] ?? $viewClassname;
	}
}
