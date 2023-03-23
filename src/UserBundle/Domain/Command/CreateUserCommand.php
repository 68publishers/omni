<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\Domain\Command;

use SixtyEightPublishers\ArchitectureBundle\Command\CommandInterface;

final class CreateUserCommand implements CommandInterface
{
    /**
     * @param array<string>        $roles
     * @param array<string, mixed> $attributes
     */
    public function __construct(
        public readonly string $username,
        public readonly ?string $password,
        public readonly string $emailAddress,
        public readonly bool $active,
        public readonly string $firstname,
        public readonly string $surname,
        public readonly array $roles,
        public readonly string $locale,
        public readonly string $timezone,
        public readonly array $attributes,
        public readonly ?string $userId = null,
    ) {}
}
