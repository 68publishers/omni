<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ForgotPasswordBundle\Domain\Command;

use SixtyEightPublishers\ArchitectureBundle\Command\CommandInterface;

final class RequestPasswordChangeCommand implements CommandInterface
{
    /**
     * @param array<string, mixed> $attributes
     */
    public function __construct(
        public readonly string $emailAddress,
        public readonly ?string $ipAddress,
        public readonly ?string $userAgent,
        public readonly ?string $passwordRequestId = null,
        public readonly array $attributes = [],
    ) {}
}
