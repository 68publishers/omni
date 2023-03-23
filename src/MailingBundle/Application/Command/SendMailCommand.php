<?php

declare(strict_types=1);

namespace SixtyEightPublishers\MailingBundle\Application\Command;

use SixtyEightPublishers\ArchitectureBundle\Command\CommandInterface;
use SixtyEightPublishers\MailingBundle\Application\Message;

final class SendMailCommand implements CommandInterface
{
    public function __construct(
        public readonly Message $message,
    ) {}
}
