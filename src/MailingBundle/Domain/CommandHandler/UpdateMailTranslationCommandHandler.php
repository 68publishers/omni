<?php

declare(strict_types=1);

namespace SixtyEightPublishers\MailingBundle\Domain\CommandHandler;

use SixtyEightPublishers\ArchitectureBundle\Command\CommandHandlerInterface;
use SixtyEightPublishers\MailingBundle\Domain\Command\UpdateMailTranslationCommand;
use SixtyEightPublishers\MailingBundle\Domain\MailRepositoryInterface;
use SixtyEightPublishers\MailingBundle\Domain\ValueObject\MailId;

final class UpdateMailTranslationCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly MailRepositoryInterface $mailRepository,
    ) {}

    public function __invoke(UpdateMailTranslationCommand $command): void
    {
        $mail = $this->mailRepository->get(MailId::fromNative($command->mailId));

        if (null !== $command->subject) {
            $mail->changeSubject($command->subject, $command->locale);
        }

        if (null !== $command->messageBody) {
            $mail->changeMessageBody($command->messageBody, $command->locale);
        }

        $this->mailRepository->save($mail);
    }
}
