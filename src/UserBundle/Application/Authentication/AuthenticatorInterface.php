<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\Application\Authentication;

interface AuthenticatorInterface
{
	/**
	 * @param string $username
	 * @param string $password
	 *
	 * @return \SixtyEightPublishers\UserBundle\Application\Authentication\Identity
	 * @throws \SixtyEightPublishers\UserBundle\Application\Exception\AuthenticationException
	 */
	public function authenticate(string $username, string $password): Identity;
}
