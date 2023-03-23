<?php

declare(strict_types=1);

namespace SixtyEightPublishers\MailingBundle\Domain\CommandHandler;

use SixtyEightPublishers\ArchitectureBundle\Command\CommandHandlerInterface;
use SixtyEightPublishers\MailingBundle\Domain\CodeGuardInterface;
use SixtyEightPublishers\MailingBundle\Domain\Command\UpdateMailCommand;
use SixtyEightPublishers\MailingBundle\Domain\MailRepositoryInterface;
use SixtyEightPublishers\MailingBundle\Domain\ValueObject\MailId;

final class UpdateMailCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly MailRepositoryInterface $mailRepository,
        private readonly ?CodeGuardInterface $codeGuard = null,
    ) {}

    public function __invoke(UpdateMailCommand $command): void
    {
        $mail = $this->mailRepository->get(MailId::fromNative($command->mailId));

        if (null !== $command->code) {
            $mail->changeCode($command->code, $this->codeGuard);
        }

        foreach ($command->getTranslations() as [$locale, $subject, $messageBody]) {
            if (null !== $subject) {
                $mail->changeSubject($subject, $locale);
            }

            if (null !== $messageBody) {
                $mail->changeMessageBody($messageBody, $locale);
            }
        }

        $this->mailRepository->save($mail);
    }
}
