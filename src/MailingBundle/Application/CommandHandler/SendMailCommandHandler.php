<?php

declare(strict_types=1);

namespace SixtyEightPublishers\MailingBundle\Application\CommandHandler;

use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use SixtyEightPublishers\ArchitectureBundle\Bus\QueryBusInterface;
use SixtyEightPublishers\ArchitectureBundle\Command\CommandHandlerInterface;
use SixtyEightPublishers\MailingBundle\Application\Address;
use SixtyEightPublishers\MailingBundle\Application\Command\SendMailCommand;
use SixtyEightPublishers\MailingBundle\Application\Exception\UnableToFindMailSourceException;
use SixtyEightPublishers\MailingBundle\Application\Exception\UnableToSendMailException;
use SixtyEightPublishers\MailingBundle\Application\MailSender\MailSenderInterface;
use SixtyEightPublishers\MailingBundle\Application\Template\TemplateExtenderInterface;
use SixtyEightPublishers\MailingBundle\Application\Template\TemplateFactoryInterface;
use SixtyEightPublishers\MailingBundle\ReadModel\Query\GetMailSourceByCodeQuery;
use SixtyEightPublishers\MailingBundle\ReadModel\View\MailSource;
use SixtyEightPublishers\TranslationBridge\Localization\TranslatorLocalizerInterface;
use function implode;
use function sprintf;

final class SendMailCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly QueryBusInterface $queryBus,
        private readonly MailSenderInterface $mailSender,
        private readonly TemplateFactoryInterface $templateFactory,
        private readonly TemplateExtenderInterface $templateExtender,
        private readonly TranslatorLocalizerInterface $translatorLocalizer,
        private readonly ?Address $defaultFrom = null,
        private readonly LoggerInterface $logger = new NullLogger(),
    ) {}

    public function __invoke(SendMailCommand $command): void
    {
        $message = $command->message;

        if (null === $message->from && null !== $this->defaultFrom) {
            $message = $message->withFrom($this->defaultFrom);
        }

        $locale = $message->locale ?? $this->translatorLocalizer->getLocale();
        $mailSource = $this->queryBus->dispatch(new GetMailSourceByCodeQuery($message->code, $locale));

        if (!$mailSource instanceof MailSource) {
            throw UnableToFindMailSourceException::create($message->code, $locale);
        }

        $messageBodyTemplate = $this->templateFactory->create($mailSource->type, $mailSource->messageBody->toNative(), $mailSource->locale->toNative());
        $messageBodyTemplate = $this->templateExtender->extend($messageBodyTemplate);
        $messageBody = $messageBodyTemplate->render($message->arguments);

        if (null !== $mailSource->subject) {
            $subjectTemplate = $this->templateFactory->create($mailSource->type, $mailSource->subject->toNative(), $mailSource->locale->toNative());
            $subjectTemplate = $this->templateExtender->extend($subjectTemplate);
            $subject = $subjectTemplate->render($message->arguments);
        }

        try {
            $this->mailSender->send($message->getHeaders(), $messageBody, $subject ?? '');

            $this->logger->info(sprintf(
                'Mail "%s" has been successfully sent from %s to %s.',
                $message->code,
                $message->from,
                implode(', ', $message->to),
            ));
        } catch (UnableToSendMailException $e) {
            $this->logger->error(sprintf(
                'Unable to send mail "%s" from %s to %s. %s',
                $message->code,
                $message->from,
                implode(', ', $message->to),
                $e->getMessage(),
            ));

            throw $e;
        }
    }
}
