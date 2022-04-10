<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\Domain;

interface PasswordHashAlgorithmInterface
{
	/**
	 * @param string $rawPassword
	 *
	 * @return string
	 * @throws \SixtyEightPublishers\UserBundle\Domain\Exception\PasswordException
	 */
	public function hash(string $rawPassword): string;
}
