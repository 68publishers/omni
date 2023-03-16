<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\Application\Authentication;

use SixtyEightPublishers\ArchitectureBundle\Bus\QueryBusInterface;
use SixtyEightPublishers\UserBundle\Application\Exception\AuthenticationException;
use SixtyEightPublishers\UserBundle\Domain\ValueObject\Password;
use SixtyEightPublishers\UserBundle\ReadModel\Query\GetCredentialsQuery;
use SixtyEightPublishers\UserBundle\ReadModel\View\Credentials;

final class Authenticator implements AuthenticatorInterface
{
    public function __construct(
        private readonly QueryBusInterface $queryBus,
    ) {}

    public function authenticate(string $username, string $password): Identity
    {
        $credentials = $this->queryBus->dispatch(new GetCredentialsQuery($username));

        if (!$credentials instanceof Credentials) {
            throw AuthenticationException::userNotFound($username);
        }

        if (!$credentials->password->verify(Password::fromNative($password))) {
            throw AuthenticationException::invalidPassword($username);
        }

        $identity = Identity::createSleeping($credentials->userId->toNative());

        return IdentityDecorator::newInstance()->wakeupIdentity($identity, $this->queryBus);
    }
}
