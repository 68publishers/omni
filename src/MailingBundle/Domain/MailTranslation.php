<?php

declare(strict_types=1);

namespace SixtyEightPublishers\MailingBundle\Domain;

use SixtyEightPublishers\MailingBundle\Domain\ValueObject\Locale;
use SixtyEightPublishers\MailingBundle\Domain\ValueObject\MessageBody;
use SixtyEightPublishers\MailingBundle\Domain\ValueObject\Subject;
use SixtyEightPublishers\MailingBundle\Domain\ValueObject\Translation;

final class MailTranslation
{
    private Subject $subject;

    private MessageBody $messageBody;

    private Locale $locale;

    public function __construct(
        private readonly Mail $mail,
        Translation $translation,
    ) {
        $this->subject = $translation->subject;
        $this->messageBody = $translation->messageBody;
        $this->locale = $translation->locale;
    }

    public function getMail(): Mail
    {
        return $this->mail;
    }

    public function getSubject(): Subject
    {
        return $this->subject;
    }

    public function getMessageBody(): MessageBody
    {
        return $this->messageBody;
    }

    public function getLocale(): Locale
    {
        return $this->locale;
    }

    public function setSubject(Subject $subject): void
    {
        $this->subject = $subject;
    }

    public function setMessageBody(MessageBody $messageBody): void
    {
        $this->messageBody = $messageBody;
    }
}
