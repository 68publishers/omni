<?php

declare(strict_types=1);

namespace SixtyEightPublishers\MailingBundle\Domain\Command;

use SixtyEightPublishers\ArchitectureBundle\Command\CommandInterface;

final class UpdateMailTranslationCommand implements CommandInterface
{
    public function __construct(
        public readonly string $mailId,
        public readonly string $locale,
        public readonly ?string $subject,
        public readonly ?string $messageBody,
    ) {}
}
