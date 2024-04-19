<?php

declare(strict_types=1);

namespace SixtyEightPublishers\MailingBundle\Domain\Event;

use SixtyEightPublishers\ArchitectureBundle\Domain\Event\AbstractDomainEvent;
use SixtyEightPublishers\MailingBundle\Domain\ValueObject\MailId;

final class MailDeleted extends AbstractDomainEvent
{
    public static function create(MailId $mailId): self
    {
        return self::occur($mailId);
    }

    public function getAggregateId(): MailId
    {
        return MailId::fromSafeNative($this->getNativeAggregatedId());
    }
}
