<?php

declare(strict_types=1);

namespace SixtyEightPublishers\MailingBundle\Bridge\Nette;

use Nette\Mail\Mailer;
use Nette\Mail\Message as MailMessage;
use Nette\Mail\SendException;
use SixtyEightPublishers\MailingBundle\Application\Exception\UnableToSendMailException;
use SixtyEightPublishers\MailingBundle\Application\MailSender\MailSenderInterface;
use SixtyEightPublishers\MailingBundle\Application\MessageHeaders;

final class MailSender implements MailSenderInterface
{
    public function __construct(
        private readonly Mailer $mailer,
    ) {}

    public function send(MessageHeaders $headers, string $messageBody, string $subject): void
    {
        $message = new MailMessage();

        $message->setFrom($headers->from->emailAddress, $headers->from->name);

        foreach ($headers->to as $address) {
            $message->addTo($address->emailAddress, $address->name);
        }

        foreach ($headers->bcc as $address) {
            $message->addBcc($address->emailAddress, $address->name);
        }

        foreach ($headers->cc as $address) {
            $message->addCc($address->emailAddress, $address->name);
        }

        foreach ($headers->replyTo as $address) {
            $message->addReplyTo($address->emailAddress, $address->name);
        }

        foreach ($headers->attachments as $attachment) {
            $message->addAttachment($attachment->file, $attachment->content, $attachment->contentType);
        }

        $message->setSubject($subject);
        $message->setHtmlBody($messageBody);

        try {
            $this->mailer->send($message);
        } catch (SendException $e) {
            throw new UnableToSendMailException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
