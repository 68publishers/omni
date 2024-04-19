<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ForgotPasswordBundle\Domain\Event;

use BadMethodCallException;
use SixtyEightPublishers\ArchitectureBundle\Domain\Event\AbstractDomainEvent;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\ValueObject\Attributes;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\ValueObject\DeviceInfo;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\ValueObject\EmailAddress;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\ValueObject\PasswordRequestId;

final class PasswordChangeCompleted extends AbstractDomainEvent
{
    private ?string $password = null;

    public static function create(
        PasswordRequestId $passwordRequestId,
        DeviceInfo $finishedDeviceInfo,
        EmailAddress $emailAddress,
        Attributes $attributes,
        string $password,
    ): self {
        $event = self::occur($passwordRequestId, [
            'finished_device_info' => $finishedDeviceInfo,
            'email_address' => $emailAddress,
            'attributes' => $attributes,
        ]);

        $event->password = $password;

        return $event;
    }

    public function getAggregateId(): PasswordRequestId
    {
        return PasswordRequestId::fromSafeNative($this->getNativeAggregatedId());
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

    public function getPassword(): string
    {
        if (null === $this->password) {
            throw new BadMethodCallException('Password unavailable due to security reasons.');
        }

        return $this->password;
    }
}
