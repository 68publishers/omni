<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ForgotPasswordBundle\Domain\Command;

use SixtyEightPublishers\ArchitectureBundle\Command\CommandInterface;

final class CancelPasswordRequestCommand implements CommandInterface
{
    /**
     * @param array<string, mixed> $attributes
     */
    public function __construct(
        public readonly string $passwordRequestId,
        public readonly ?string $ipAddress,
        public readonly ?string $userAgent,
        public readonly array $attributes = [],
    ) {}
}
