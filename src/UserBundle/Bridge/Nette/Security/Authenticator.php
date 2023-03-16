<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\Bridge\Nette\Security;

use Nette\Security\Authenticator as NetteAuthenticatorInterface;
use Nette\Security\IdentityHandler as NetteIdentityHandlerInterface;
use Nette\Security\IIdentity as NetteIdentityInterface;
use SixtyEightPublishers\ArchitectureBundle\Bus\QueryBusInterface;
use SixtyEightPublishers\UserBundle\Application\Authentication\AuthenticatorInterface;
use SixtyEightPublishers\UserBundle\Application\Authentication\IdentityDecorator;
use SixtyEightPublishers\UserBundle\Application\Exception\AuthenticationException;
use SixtyEightPublishers\UserBundle\Application\Exception\IdentityException;
use function assert;

final class Authenticator implements NetteAuthenticatorInterface, NetteIdentityHandlerInterface
{
    public function __construct(
        private readonly QueryBusInterface $queryBus,
        private readonly AuthenticatorInterface $authenticator,
    ) {}

    /**
     * @throws AuthenticationException
     */
    public function authenticate(string $user, string $password): NetteIdentityInterface
    {
        $identity = $this->authenticator->authenticate($user, $password);
        $identity = Identity::of($identity);

        try {
            $identity->getData();
        } catch (IdentityException $e) {
            throw AuthenticationException::fromIdentityException($e);
        }

        return $identity;
    }

    public function sleepIdentity(NetteIdentityInterface $identity): NetteIdentityInterface
    {
        $sleepingIdentity = IdentityDecorator::newInstance()->sleepIdentity($this->transformIdentity($identity));
        assert($sleepingIdentity instanceof NetteIdentityInterface);

        return $sleepingIdentity;
    }

    public function wakeupIdentity(NetteIdentityInterface $identity): ?NetteIdentityInterface
    {
        $wakeupIdentity = IdentityDecorator::newInstance()->wakeupIdentity($this->transformIdentity($identity), $this->queryBus);
        assert($wakeupIdentity instanceof NetteIdentityInterface);

        try {
            $wakeupIdentity->getData();
        } catch (IdentityException $e) {
            return null;
        }

        return $wakeupIdentity;
    }

    private function transformIdentity(NetteIdentityInterface $identity): Identity
    {
        return !$identity instanceof Identity ? Identity::createSleeping($identity->getId()) : $identity;
    }
}
