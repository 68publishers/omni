<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\Application\Authentication;

use SixtyEightPublishers\UserBundle\Domain\ValueObject\Password;
use SixtyEightPublishers\ArchitectureBundle\Bus\QueryBusInterface;
use SixtyEightPublishers\UserBundle\ReadModel\View\CredentialsView;
use SixtyEightPublishers\UserBundle\ReadModel\Query\GetCredentialsQuery;
use SixtyEightPublishers\UserBundle\Application\Exception\AuthenticationException;

final class Authenticator implements AuthenticatorInterface
{
	private QueryBusInterface $queryBus;

	/**
	 * @param \SixtyEightPublishers\ArchitectureBundle\Bus\QueryBusInterface $queryBus
	 */
	public function __construct(QueryBusInterface $queryBus)
	{
		$this->queryBus = $queryBus;
	}

	/**
	 * {@inheritDoc}
	 */
	public function authenticate(string $username, string $password): Identity
	{
		$credentials = $this->queryBus->dispatch(GetCredentialsQuery::create($username));

		if (!$credentials instanceof CredentialsView) {
			throw AuthenticationException::userNotFound($username);
		}

		if (NULL === $credentials->password || !$credentials->password->verify(Password::fromValue($password))) {
			throw AuthenticationException::invalidPassword($username);
		}

		$identity = Identity::createSleeping($credentials->id);
		$identityDecorator = IdentityDecorator::newInstance();

		return $identityDecorator->wakeupIdentity($identity, $this->queryBus);
	}
}
