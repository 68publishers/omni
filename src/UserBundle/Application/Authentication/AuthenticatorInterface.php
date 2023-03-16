<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\Application\Authentication;

use SixtyEightPublishers\UserBundle\Application\Exception\AuthenticationException;

interface AuthenticatorInterface
{
    /**
     * @throws AuthenticationException
     */
    public function authenticate(string $username, string $password): Identity;
}
