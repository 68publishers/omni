<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\ReadModel\View;

interface ViewClassnameTranslatorInterface
{
	/**
	 * @param string $viewClassname
	 * @param string $extendedViewClassname
	 *
	 * @return void
	 */
	public function register(string $viewClassname, string $extendedViewClassname): void;

	/**
	 * @param string $viewClassname
	 *
	 * @return string
	 */
	public function translate(string $viewClassname): string;
}
