<?php

declare(strict_types=1);

namespace SixtyEightPublishers\MailingBundle\Domain\Event;

use SixtyEightPublishers\ArchitectureBundle\Domain\Event\AbstractDomainEvent;
use SixtyEightPublishers\MailingBundle\Domain\ValueObject\Code;
use SixtyEightPublishers\MailingBundle\Domain\ValueObject\MailId;
use SixtyEightPublishers\MailingBundle\Domain\ValueObject\Translations;

final class MailCreated extends AbstractDomainEvent
{
    public static function create(
        MailId $mailId,
        Code $code,
        Translations $translations,
    ): self {
        return self::occur($mailId, [
            'code' => $code,
            'translations' => $translations,
        ]);
    }

    public function getAggregateId(): MailId
    {
        return MailId::fromSafeNative($this->getNativeAggregatedId());
    }

    public function getCode(): Code
    {
        return Code::fromNative($this->parameters['code']);
    }

    public function getTranslations(): Translations
    {
        return Translations::fromNative($this->parameters['translations']);
    }
}
