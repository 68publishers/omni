<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ForgotPasswordBundle\Domain\Event;

use SixtyEightPublishers\ArchitectureBundle\Domain\Event\AbstractDomainEvent;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\ValueObject\Attributes;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\ValueObject\DeviceInfo;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\ValueObject\EmailAddress;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\ValueObject\PasswordRequestId;

final class PasswordChangeCanceled extends AbstractDomainEvent
{
    public static function create(
        PasswordRequestId $passwordRequestId,
        DeviceInfo $finishedDeviceInfo,
        EmailAddress $emailAddress,
        Attributes $attributes,
    ): self {
        return self::occur($passwordRequestId->toNative(), [
            'finished_device_info' => $finishedDeviceInfo,
            'email_address' => $emailAddress,
            'attributes' => $attributes,
        ]);
    }

    public function getFinishedDeviceInfo(): DeviceInfo
    {
        return DeviceInfo::fromNative($this->parameters['finished_device_info']);
    }

    public function getEmailAddress(): EmailAddress
    {
        return EmailAddress::fromNative($this->parameters['email_address']);
    }

    public function getAttributes(): Attributes
    {
        return Attributes::fromNative($this->parameters['attributes']);
    }
}
