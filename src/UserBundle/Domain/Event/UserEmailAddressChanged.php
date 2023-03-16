<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\Domain\Event;

use SixtyEightPublishers\ArchitectureBundle\Domain\Event\AbstractDomainEvent;
use SixtyEightPublishers\UserBundle\Domain\ValueObject\EmailAddress;
use SixtyEightPublishers\UserBundle\Domain\ValueObject\UserId;

final class UserEmailAddressChanged extends AbstractDomainEvent
{
    public static function create(UserId $userId, EmailAddress $emailAddress): self
    {
        return self::occur($userId->toNative(), [
            'email_address' => $emailAddress,
        ]);
    }

    public function getEmailAddress(): EmailAddress
    {
        return EmailAddress::fromNative($this->parameters['email_address']);
    }
}
