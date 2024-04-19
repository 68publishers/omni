<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ForgotPasswordBundle\Domain;

use DateTimeImmutable;
use DateTimeZone;
use Exception;
use SixtyEightPublishers\ArchitectureBundle\Domain\AggregateRootInterface;
use SixtyEightPublishers\ArchitectureBundle\Domain\AggregateRootTrait;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\Command\CancelPasswordRequestCommand;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\Command\CompletePasswordRequestCommand;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\Command\RequestPasswordChangeCommand;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\Event\PasswordChangeCanceled;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\Event\PasswordChangeCompleted;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\Event\PasswordChangeRequested;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\Exception\PasswordRequestExpiredException;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\Exception\PasswordStatusChangeException;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\ValueObject\Attributes;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\ValueObject\DeviceInfo;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\ValueObject\EmailAddress;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\ValueObject\IpAddress;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\ValueObject\PasswordRequestId;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\ValueObject\Status;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\ValueObject\UserAgent;

class PasswordRequest implements AggregateRootInterface
{
    use AggregateRootTrait;

    protected PasswordRequestId $id;
    
    protected EmailAddress $emailAddress;

    protected Status $status;

    protected DateTimeImmutable $requestedAt;

    protected DateTimeImmutable $expiredAt;

    protected ?DateTimeImmutable $finishedAt = null;

    protected DeviceInfo $requestDeviceInfo;

    protected DeviceInfo $finishedDeviceInfo;

    protected Attributes $attributes;

    /**
     * @throws Exception
     */
    public static function requestPasswordChange(
        RequestPasswordChangeCommand $command,
        PasswordRequestExpirationProviderInterface $expirationProvider,
        ?EmailAddressGuardInterface $emailAddressGuard = null,
    ): static {
        $passwordRequest = new static(); // @phpstan-ignore-line

        $passwordRequestId = null !== $command->passwordRequestId ? PasswordRequestId::fromNative($command->passwordRequestId) : PasswordRequestId::new();
        $emailAddress = EmailAddress::fromNative($command->emailAddress);
        $deviceInfo = new DeviceInfo(
            null !== $command->ipAddress ? IpAddress::fromNative($command->ipAddress) : null,
            null !== $command->userAgent ? UserAgent::fromNative($command->userAgent) : null,
        );
        $attributes = Attributes::fromNative($command->attributes);

        $emailAddressGuard && $emailAddressGuard($passwordRequestId, $emailAddress);

        $passwordRequest->recordThat(PasswordChangeRequested::create(
            $passwordRequestId,
            $emailAddress,
            $deviceInfo,
            $expirationProvider->provideExpiration(new DateTimeImmutable('now', new DateTimeZone('UTC'))),
            $attributes,
        ));

        return $passwordRequest;
    }

    /**
     * @throws PasswordRequestExpiredException
     */
    public function complete(CompletePasswordRequestCommand $command): void
    {
        if ($this->status->isFinished()) {
            throw PasswordStatusChangeException::cantCompleteBecauseRequestIsAlreadyFinished();
        }

        if ($this->isExpired()) {
            throw PasswordRequestExpiredException::create();
        }

        $this->recordThat(PasswordChangeCompleted::create(
            $this->id,
            new DeviceInfo(
                null !== $command->ipAddress ? IpAddress::fromNative($command->ipAddress) : null,
                null !== $command->userAgent ? UserAgent::fromNative($command->userAgent) : null,
            ),
            $this->emailAddress,
            Attributes::fromNative($command->attributes),
            $command->password,
        ));
    }

    public function cancel(CancelPasswordRequestCommand $command): void
    {
        if ($this->status->isFinished()) {
            throw PasswordStatusChangeException::cantCancelBecauseRequestIsAlreadyFinished();
        }

        $this->recordThat(PasswordChangeCanceled::create(
            $this->id,
            new DeviceInfo(
                null !== $command->ipAddress ? IpAddress::fromNative($command->ipAddress) : null,
                null !== $command->userAgent ? UserAgent::fromNative($command->userAgent) : null,
            ),
            $this->emailAddress,
            Attributes::fromNative($command->attributes),
        ));
    }

    public function getAggregateId(): PasswordRequestId
    {
        return $this->id;
    }

    public function isExpired(): bool
    {
        try {
            return new DateTimeImmutable('now', new DateTimeZone('UTC')) > $this->expiredAt;
        } catch (Exception $e) { // @phpstan-ignore-line
            return true;
        }
    }

    /**
     * @throws Exception
     */
    protected function whenPasswordChangeRequested(PasswordChangeRequested $event): void
    {
        $this->id = $event->getAggregateId();
        $this->emailAddress = $event->getEmailAddress();
        $this->status = Status::REQUESTED;
        $this->requestedAt = $event->getCreatedAt();
        $this->expiredAt = $event->getExpiredAt();
        $this->requestDeviceInfo = $event->getRequestDeviceInfo();
        $this->finishedDeviceInfo = new DeviceInfo(null, null);
        $this->attributes = $event->getAttributes();
    }

    /**
     * @throws Exception
     */
    protected function whenPasswordChangeCompleted(PasswordChangeCompleted $event): void
    {
        $this->status = Status::COMPLETED;
        $this->finishedDeviceInfo = $event->getFinishedDeviceInfo();
        $this->finishedAt = new DateTimeImmutable('now', new DateTimeZone('UTC'));
        $this->attributes = $this->attributes->merge($event->getAttributes());
    }

    /**
     * @throws Exception
     */
    protected function whenPasswordChangeCanceled(PasswordChangeCanceled $event): void
    {
        $this->status = Status::CANCELED;
        $this->finishedDeviceInfo = $event->getFinishedDeviceInfo();
        $this->finishedAt = new DateTimeImmutable('now', new DateTimeZone('UTC'));
        $this->attributes = $this->attributes->merge($event->getAttributes());
    }
}
