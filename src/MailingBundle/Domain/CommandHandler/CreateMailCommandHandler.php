<?php

declare(strict_types=1);

namespace SixtyEightPublishers\MailingBundle\Domain\CommandHandler;

use SixtyEightPublishers\ArchitectureBundle\Command\CommandHandlerInterface;
use SixtyEightPublishers\MailingBundle\Domain\CodeGuardInterface;
use SixtyEightPublishers\MailingBundle\Domain\Command\CreateMailCommand;
use SixtyEightPublishers\MailingBundle\Domain\Mail;
use SixtyEightPublishers\MailingBundle\Domain\MailRepositoryInterface;

final class CreateMailCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly MailRepositoryInterface $mailRepository,
        private readonly ?CodeGuardInterface $codeGuard = null,
    ) {}

    public function __invoke(CreateMailCommand $command): void
    {
        $mail = Mail::create($command, $this->codeGuard);

        $this->mailRepository->save($mail);
    }
}
