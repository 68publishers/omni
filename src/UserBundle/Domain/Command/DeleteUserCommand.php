<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\Domain\Command;

use SixtyEightPublishers\ArchitectureBundle\Command\CommandInterface;

final class DeleteUserCommand implements CommandInterface
{
    public function __construct(
        public readonly string $userId,
    ) {}
}
