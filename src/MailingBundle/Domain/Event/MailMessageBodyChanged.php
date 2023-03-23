<?php

declare(strict_types=1);

namespace SixtyEightPublishers\MailingBundle\Domain\Event;

use SixtyEightPublishers\ArchitectureBundle\Domain\Event\AbstractDomainEvent;
use SixtyEightPublishers\MailingBundle\Domain\ValueObject\Locale;
use SixtyEightPublishers\MailingBundle\Domain\ValueObject\MailId;
use SixtyEightPublishers\MailingBundle\Domain\ValueObject\MessageBody;

final class MailMessageBodyChanged extends AbstractDomainEvent
{
    public static function create(MailId $mailId, MessageBody $messageBody, Locale $locale): self
    {
        return self::occur($mailId->toNative(), [
            'message_body' => $messageBody,
            'locale' => $locale,
        ]);
    }

    public function getMessageBody(): MessageBody
    {
        return MessageBody::fromNative($this->parameters['message_body']);
    }

    public function getLocale(): Locale
    {
        return Locale::fromNative($this->parameters['locale']);
    }
}
