<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\Bridge\Nette\Security;

use Nette\Security\IIdentity as NetteIdentityInterface;
use SixtyEightPublishers\UserBundle\Domain\ValueObject\UserId;
use Nette\Security\Authenticator as NetteAuthenticatorInterface;
use SixtyEightPublishers\ArchitectureBundle\Bus\QueryBusInterface;
use Nette\Security\IdentityHandler as NetteIdentityHandlerInterface;
use SixtyEightPublishers\UserBundle\Application\Exception\IdentityException;
use SixtyEightPublishers\UserBundle\Application\Authentication\IdentityDecorator;
use SixtyEightPublishers\UserBundle\Application\Exception\AuthenticationException;
use SixtyEightPublishers\UserBundle\Application\Authentication\AuthenticatorInterface;

final class Authenticator implements NetteAuthenticatorInterface, NetteIdentityHandlerInterface
{
	private QueryBusInterface $queryBus;

	private AuthenticatorInterface $authenticator;

	/**
	 * @param \SixtyEightPublishers\ArchitectureBundle\Bus\QueryBusInterface                     $queryBus
	 * @param \SixtyEightPublishers\UserBundle\Application\Authentication\AuthenticatorInterface $authenticator
	 */
	public function __construct(QueryBusInterface $queryBus, AuthenticatorInterface $authenticator)
	{
		$this->queryBus = $queryBus;
		$this->authenticator = $authenticator;
	}

	/**
	 * {@inheritDoc}
	 *
	 * @throws \SixtyEightPublishers\UserBundle\Application\Exception\AuthenticationException
	 */
	public function authenticate(string $user, string $password): NetteIdentityInterface
	{
		$identity = $this->authenticator->authenticate($user, $password);
		$identity = Identity::of($identity);

		try {
			$identity->data();
		} catch (IdentityException $e) {
			throw AuthenticationException::fromIdentityException($e);
		}

		return $identity;
	}

	/**
	 * @param \Nette\Security\IIdentity $identity
	 *
	 * @return \Nette\Security\IIdentity
	 */
	public function sleepIdentity(NetteIdentityInterface $identity): NetteIdentityInterface
	{
		$sleepingIdentity = IdentityDecorator::newInstance()->sleepIdentity($this->transformIdentity($identity));
		assert($sleepingIdentity instanceof NetteIdentityInterface);

		return $sleepingIdentity;
	}

	/**
	 * @param \Nette\Security\IIdentity $identity
	 *
	 * @return \Nette\Security\IIdentity|NULL
	 */
	public function wakeupIdentity(NetteIdentityInterface $identity): ?NetteIdentityInterface
	{
		$wakeupIdentity = IdentityDecorator::newInstance()->wakeupIdentity($this->transformIdentity($identity), $this->queryBus);
		assert($wakeupIdentity instanceof NetteIdentityInterface);

		try {
			$wakeupIdentity->data();
		} catch (IdentityException $e) {
			return NULL;
		}

		return $wakeupIdentity;
	}

	/**
	 * @param \Nette\Security\IIdentity $identity
	 *
	 * @return \SixtyEightPublishers\UserBundle\Bridge\Nette\Security\Identity
	 */
	private function transformIdentity(NetteIdentityInterface $identity): Identity
	{
		return !$identity instanceof Identity ? Identity::createSleeping(UserId::fromString($identity->getId())) : $identity;
	}
}
