<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ForgotPasswordBundle\Domain\Event;

use DateTimeImmutable;
use DateTimeInterface;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\ValueObject\IpAddress;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\ValueObject\UserAgent;
use SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\EmailAddress;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\ValueObject\DeviceInfo;
use SixtyEightPublishers\ArchitectureBundle\Domain\Event\AbstractDomainEvent;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\ValueObject\PasswordRequestId;
use SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\EmailAddressInterface;

final class PasswordChangeRequested extends AbstractDomainEvent
{
	private PasswordRequestId $passwordRequestId;

	private EmailAddressInterface $emailAddress;

	private DeviceInfo $requestDeviceInfo;

	private DateTimeImmutable $expiredAt;

	/**
	 * @param \SixtyEightPublishers\ForgotPasswordBundle\Domain\ValueObject\PasswordRequestId   $passwordRequestId
	 * @param \SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\EmailAddressInterface $emailAddress
	 * @param \SixtyEightPublishers\ForgotPasswordBundle\Domain\ValueObject\DeviceInfo          $requestDeviceInfo
	 * @param \DateTimeImmutable                                                                $expiredAt
	 *
	 * @return static
	 */
	public static function create(PasswordRequestId $passwordRequestId, EmailAddressInterface $emailAddress, DeviceInfo $requestDeviceInfo, DateTimeImmutable $expiredAt): self
	{
		$event = self::occur($passwordRequestId->toString(), [
			'email_address' => $emailAddress->value(),
			'request_ip_address' => $requestDeviceInfo->ipAddress()->value(),
			'request_user_agent' => $requestDeviceInfo->userAgent()->value(),
			'expired_at' => $expiredAt->format(DateTimeInterface::ATOM),
		]);

		$event->passwordRequestId = $passwordRequestId;
		$event->emailAddress = $emailAddress;
		$event->requestDeviceInfo = $requestDeviceInfo;
		$event->expiredAt = $expiredAt;

		return $event;
	}

	/**
	 * @return \SixtyEightPublishers\ForgotPasswordBundle\Domain\ValueObject\PasswordRequestId
	 */
	public function passwordRequestId(): PasswordRequestId
	{
		return $this->passwordRequestId;
	}

	/**
	 * @return \SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\EmailAddressInterface
	 */
	public function emailAddress(): EmailAddressInterface
	{
		return $this->emailAddress;
	}

	/**
	 * @return \SixtyEightPublishers\ForgotPasswordBundle\Domain\ValueObject\DeviceInfo
	 */
	public function requestDeviceInfo(): DeviceInfo
	{
		return $this->requestDeviceInfo;
	}

	/**
	 * @return \DateTimeImmutable
	 */
	public function expiredAt(): DateTimeImmutable
	{
		return $this->expiredAt;
	}

	/**
	 * {@inheritDoc}
	 *
	 * @throws \Exception
	 */
	protected function reconstituteState(array $parameters): void
	{
		$this->passwordRequestId = PasswordRequestId::fromUuid($this->aggregateId()->id());
		$this->emailAddress = EmailAddress::fromValue($parameters['email_address']);
		$this->requestDeviceInfo = DeviceInfo::create(
			IpAddress::fromValue($parameters['request_ip_address']),
			UserAgent::fromValue($parameters['request_user_agent'])
		);
		$this->expiredAt = new DateTimeImmutable($parameters['expired_at']);
	}
}
