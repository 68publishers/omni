<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ForgotPasswordBundle\Domain\Aggregate;

use Exception;
use DateTimeZone;
use DateTimeImmutable;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\ValueObject\Status;
use SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\AggregateId;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\ValueObject\IpAddress;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\ValueObject\UserAgent;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\ValueObject\DeviceInfo;
use SixtyEightPublishers\ArchitectureBundle\Domain\Aggregate\AggregateRootTrait;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\ValueObject\ValidIpAddress;
use SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\ValidEmailAddress;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\Event\PasswordChangeCanceled;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\Event\PasswordChangeCompleted;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\Event\PasswordChangeRequested;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\ValueObject\PasswordRequestId;
use SixtyEightPublishers\ArchitectureBundle\Domain\Aggregate\AggregateRootInterface;
use SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\EmailAddressInterface;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\CheckEmailAddressExistsInterface;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\Command\CancelPasswordRequestCommand;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\Command\RequestPasswordChangeCommand;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\Command\CompletePasswordRequestCommand;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\Exception\PasswordStatusChangeException;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\Exception\PasswordRequestExpiredException;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\PasswordRequestExpirationProviderInterface;

class PasswordRequest implements AggregateRootInterface
{
	use AggregateRootTrait;

	protected PasswordRequestId $id;
	
	protected EmailAddressInterface $emailAddress;

	protected Status $status;

	protected DateTimeImmutable $requestedAt;

	protected DateTimeImmutable $expiredAt;

	protected ?DateTimeImmutable $finishedAt = NULL;

	protected DeviceInfo $requestDeviceInfo;

	protected DeviceInfo $finishedDeviceInfo;

	/**
	 * @param \SixtyEightPublishers\ForgotPasswordBundle\Domain\Command\RequestPasswordChangeCommand       $command
	 * @param \SixtyEightPublishers\ForgotPasswordBundle\Domain\PasswordRequestExpirationProviderInterface $expirationProvider
	 * @param \SixtyEightPublishers\ForgotPasswordBundle\Domain\CheckEmailAddressExistsInterface           $checkEmailAddressExists
	 *
	 * @return static
	 * @throws \Exception
	 */
	public static function requestPasswordChange(RequestPasswordChangeCommand $command, PasswordRequestExpirationProviderInterface $expirationProvider, CheckEmailAddressExistsInterface $checkEmailAddressExists): self
	{
		$passwordRequest = new static();

		$passwordRequestId = NULL !== $command->passwordRequestId() ? PasswordRequestId::fromString($command->passwordRequestId()) : PasswordRequestId::new();
		$emailAddress = ValidEmailAddress::fromValue($command->emailAddress());
		$deviceInfo = DeviceInfo::create(
			ValidIpAddress::fromValue($command->ipAddress(), TRUE),
			UserAgent::fromValue($command->userAgent())
		);

		$checkEmailAddressExists($emailAddress);

		$passwordRequest->recordThat(PasswordChangeRequested::create(
			$passwordRequestId,
			$emailAddress,
			$deviceInfo,
			$expirationProvider->provideExpiration(new DateTimeImmutable('now', new DateTimeZone('UTC')))
		));

		return $passwordRequest;
	}

	/**
	 * @return bool
	 */
	public function expired(): bool
	{
		try {
			return new DateTimeImmutable('now', new DateTimeZone('UTC')) > $this->expiredAt;
		} catch (Exception $e) {
			return TRUE;
		}
	}

	/**
	 * @param \SixtyEightPublishers\ForgotPasswordBundle\Domain\Command\CompletePasswordRequestCommand $command
	 *
	 * @return void
	 * @throws \SixtyEightPublishers\ForgotPasswordBundle\Domain\Exception\PasswordRequestExpiredException
	 */
	public function complete(CompletePasswordRequestCommand $command): void
	{
		if ($this->status->isFinished()) {
			throw PasswordStatusChangeException::cantCompleteBecauseRequestIsAlreadyFinished();
		}

		if ($this->expired()) {
			throw PasswordRequestExpiredException::create();
		}

		$deviceInfo = DeviceInfo::create(
			ValidIpAddress::fromValue($command->ipAddress(), TRUE),
			UserAgent::fromValue($command->userAgent())
		);

		$this->recordThat(PasswordChangeCompleted::create($this->id, $deviceInfo, $this->emailAddress, $command->password()));
	}

	/**
	 * @param \SixtyEightPublishers\ForgotPasswordBundle\Domain\Command\CancelPasswordRequestCommand $command
	 *
	 * @return void
	 */
	public function cancel(CancelPasswordRequestCommand $command): void
	{
		if ($this->status->isFinished()) {
			throw PasswordStatusChangeException::cantCancelBecauseRequestIsAlreadyFinished();
		}

		$deviceInfo = DeviceInfo::create(
			ValidIpAddress::fromValue($command->ipAddress(), TRUE),
			UserAgent::fromValue($command->userAgent())
		);

		$this->recordThat(PasswordChangeCanceled::create($this->id, $deviceInfo, $this->emailAddress));
	}

	/**
	 * {@inheritDoc}
	 */
	public function aggregateId(): AggregateId
	{
		return AggregateId::fromUuid($this->id->id());
	}

	/**
	 * @param \SixtyEightPublishers\ForgotPasswordBundle\Domain\Event\PasswordChangeRequested $event
	 *
	 * @return void
	 */
	protected function whenPasswordChangeRequested(PasswordChangeRequested $event): void
	{
		$this->id = $event->passwordRequestId();
		$this->emailAddress = $event->emailAddress();
		$this->status = Status::REQUESTED();
		$this->requestedAt = $event->createdAt();
		$this->expiredAt = $event->expiredAt();
		$this->requestDeviceInfo = $event->requestDeviceInfo();
		$this->finishedDeviceInfo = DeviceInfo::create(IpAddress::fromValue(''), UserAgent::fromValue(''));
	}

	/**
	 * @param \SixtyEightPublishers\ForgotPasswordBundle\Domain\Event\PasswordChangeCompleted $event
	 *
	 * @return void
	 * @throws \Exception
	 */
	protected function whenPasswordChangeCompleted(PasswordChangeCompleted $event): void
	{
		$this->status = Status::COMPLETED();
		$this->finishedDeviceInfo = $event->finishedDeviceInfo();
		$this->finishedAt = new DateTimeImmutable('now', new DateTimeZone('UTC'));
	}

	/**
	 * @param \SixtyEightPublishers\ForgotPasswordBundle\Domain\Event\PasswordChangeCanceled $event
	 *
	 * @return void
	 * @throws \Exception
	 */
	protected function whenPasswordChangeCanceled(PasswordChangeCanceled $event): void
	{
		$this->status = Status::CANCELED();
		$this->finishedDeviceInfo = $event->finishedDeviceInfo();
		$this->finishedAt = new DateTimeImmutable('now', new DateTimeZone('UTC'));
	}
}
