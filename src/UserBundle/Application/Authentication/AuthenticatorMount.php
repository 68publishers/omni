<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\Application\Authentication;

use SixtyEightPublishers\UserBundle\Application\Exception\AuthenticationException;
use SixtyEightPublishers\UserBundle\Application\Exception\AuthenticatorResolvingException;
use function explode;
use function str_contains;

final class AuthenticatorMount implements AuthenticatorInterface
{
    public const SEPARATOR = '://';

    /** @var array<AuthenticatorInterface> */
    private array $authenticators = [];

    /**
     * @param array<AuthenticatorInterface> $authenticators
     */
    public function __construct(
        array $authenticators,
        private readonly ?AuthenticatorInterface $defaultAuthenticator = null,
    ) {
        foreach ($authenticators as $name => $authenticator) {
            $this->addAuthenticator((string) $name, $authenticator);
        }
    }

    /**
     * @throws AuthenticatorResolvingException|AuthenticationException
     */
    public function authenticate(string $username, string $password): Identity
    {
        if (!str_contains($username, self::SEPARATOR)) {
            if (null === $this->defaultAuthenticator) {
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

    private function addAuthenticator(string $name, AuthenticatorInterface $authenticator): void
    {
        $this->authenticators[$name] = $authenticator;
    }
}
