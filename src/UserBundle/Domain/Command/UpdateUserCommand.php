<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\Domain\Command;

use SixtyEightPublishers\ArchitectureBundle\Command\CommandInterface;

final class UpdateUserCommand implements CommandInterface
{
    /**
     * @param array<string>        $roles
     * @param array<string, mixed> $attributes
     */
    public function __construct(
        public readonly string $userId,
        public readonly ?string $username = null,
        public readonly ?string $password = null,
        public readonly ?string $emailAddress = null,
        public readonly ?bool $active = null,
        public readonly ?string $firstname = null,
        public readonly ?string $surname = null,
        public readonly ?array $roles = null,
        public readonly ?string $locale = null,
        public readonly ?string $timezone = null,
        public readonly ?array $attributes = null,
    ) {}
}
