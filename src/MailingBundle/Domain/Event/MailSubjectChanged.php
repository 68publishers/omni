<?php

declare(strict_types=1);

namespace SixtyEightPublishers\MailingBundle\Domain\Event;

use SixtyEightPublishers\ArchitectureBundle\Domain\Event\AbstractDomainEvent;
use SixtyEightPublishers\MailingBundle\Domain\ValueObject\Locale;
use SixtyEightPublishers\MailingBundle\Domain\ValueObject\MailId;
use SixtyEightPublishers\MailingBundle\Domain\ValueObject\Subject;

final class MailSubjectChanged extends AbstractDomainEvent
{
    public static function create(MailId $mailId, Subject $subject, Locale $locale): self
    {
        return self::occur($mailId->toNative(), [
            'subject' => $subject,
            'locale' => $locale,
        ]);
    }

    public function getSubject(): Subject
    {
        return Subject::fromNative($this->parameters['subject']);
    }

    public function getLocale(): Locale
    {
        return Locale::fromNative($this->parameters['locale']);
    }
}
