<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\Domain;

use SixtyEightPublishers\UserBundle\Domain\Exception\PasswordException;

interface PasswordHashAlgorithmInterface
{
    /**
     * @throws PasswordException
     */
    public function hash(string $rawPassword): string;
}
