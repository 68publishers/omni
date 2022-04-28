<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\Application\Csrf;

interface CsrfTokenFactoryInterface
{
	/**
	 * @param string $component
	 *
	 * @return string
	 */
	public function create(string $component = ''): string;
}
