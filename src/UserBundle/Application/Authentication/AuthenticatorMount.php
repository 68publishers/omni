<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\Application\Authentication;

use SixtyEightPublishers\UserBundle\Application\Exception\AuthenticatorResolvingException;

final class AuthenticatorMount implements AuthenticatorInterface
{
	public const SEPARATOR = '://';

	/** @var \SixtyEightPublishers\UserBundle\Application\Authentication\AuthenticatorInterface[]  */
	private array $authenticators = [];

	private ?AuthenticatorInterface $defaultAuthenticator;

	/**
	 * @param \SixtyEightPublishers\UserBundle\Application\Authentication\AuthenticatorInterface[]    $authenticators
	 * @param \SixtyEightPublishers\UserBundle\Application\Authentication\AuthenticatorInterface|NULL $defaultAuthenticator
	 */
	public function __construct(array $authenticators, ?AuthenticatorInterface $defaultAuthenticator = NULL)
	{
		foreach ($authenticators as $name => $authenticator) {
			$this->addAuthenticator((string) $name, $authenticator);
		}

		$this->defaultAuthenticator = $defaultAuthenticator;
	}

	/**
	 * {@inheritDoc}
	 *
	 * @throws \SixtyEightPublishers\UserBundle\Application\Exception\AuthenticatorResolvingException
	 */
	public function authenticate(string $username, string $password): Identity
	{
		if (FALSE === strpos($username, self::SEPARATOR)) {
			if (NULL === $this->defaultAuthenticator) {
				throw AuthenticatorResolvingException::missingDefault();
			}

			return $this->defaultAuthenticator->authenticate($username, $password);
		}

		[$authenticatorName, $username] = explode(self::SEPARATOR, $username, 2);

		if (!isset($this->authenticators[$authenticatorName])) {
			throw AuthenticatorResolvingException::missingAuthenticator($authenticatorName);
		}

		return $this->authenticators[$authenticatorName]->authenticate($username, $password);
	}

	/**
	 * @param string                                                                             $name
	 * @param \SixtyEightPublishers\UserBundle\Application\Authentication\AuthenticatorInterface $authenticator
	 *
	 * @return void
	 */
	private function addAuthenticator(string $name, AuthenticatorInterface $authenticator): void
	{
		$this->authenticators[$name] = $authenticator;
	}
}
