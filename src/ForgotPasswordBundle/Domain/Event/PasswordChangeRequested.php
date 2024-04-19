<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ForgotPasswordBundle\Domain\Event;

use DateTimeImmutable;
use DateTimeInterface;
use Exception;
use SixtyEightPublishers\ArchitectureBundle\Domain\Event\AbstractDomainEvent;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\ValueObject\Attributes;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\ValueObject\DeviceInfo;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\ValueObject\EmailAddress;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\ValueObject\PasswordRequestId;

final class PasswordChangeRequested extends AbstractDomainEvent
{
    public static function create(
        PasswordRequestId $passwordRequestId,
        EmailAddress $emailAddress,
        DeviceInfo $requestDeviceInfo,
        DateTimeImmutable $expiredAt,
        Attributes $attributes,
    ): self {
        return self::occur($passwordRequestId, [
            'email_address' => $emailAddress,
            'request_device_info' => $requestDeviceInfo,
            'expired_at' => $expiredAt->format(DateTimeInterface::ATOM),
            'attributes' => $attributes,
        ]);
    }

    public function getAggregateId(): PasswordRequestId
    {
        return PasswordRequestId::fromSafeNative($this->getNativeAggregatedId());
    }

    public function getEmailAddress(): EmailAddress
    {
        return EmailAddress::fromNative($this->parameters['email_address']);
    }

    public function getRequestDeviceInfo(): DeviceInfo
    {
        return DeviceInfo::fromNative($this->parameters['request_device_info']);
    }

    /**
     * @throws Exception
     */
    public function getExpiredAt(): DateTimeImmutable
    {
        return new DateTimeImmutable($this->parameters['expired_at']);
    }

    public function getAttributes(): Attributes
    {
        return Attributes::fromNative($this->parameters['attributes']);
    }
}
