<?php

declare(strict_types=1);

namespace SixtyEightPublishers\MailingBundle\Domain\Exception;

use DomainException;
use SixtyEightPublishers\MailingBundle\Domain\ValueObject\MailId;
use function sprintf;

final class MailNotFoundException extends DomainException
{
    public static function withId(MailId $id): self
    {
        return new self(sprintf(
            'Mail with the ID %s not found.',
            $id->toNative(),
        ));
    }
}
