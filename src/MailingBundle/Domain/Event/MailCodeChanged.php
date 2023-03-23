<?php

declare(strict_types=1);

namespace SixtyEightPublishers\MailingBundle\Domain\Event;

use SixtyEightPublishers\ArchitectureBundle\Domain\Event\AbstractDomainEvent;
use SixtyEightPublishers\MailingBundle\Domain\ValueObject\Code;
use SixtyEightPublishers\MailingBundle\Domain\ValueObject\MailId;

final class MailCodeChanged extends AbstractDomainEvent
{
    public static function create(MailId $mailId, Code $code): self
    {
        return self::occur($mailId->toNative(), [
            'code' => $code,
        ]);
    }

    public function getCode(): Code
    {
        return Code::fromNative($this->parameters['code']);
    }
}
