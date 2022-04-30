<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ForgotPasswordBundle\Domain\Event;

use SixtyEightPublishers\ForgotPasswordBundle\Domain\Dto\IpAddress;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\Dto\UserAgent;
use SixtyEightPublishers\ArchitectureBundle\Domain\Dto\EmailAddress;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\Dto\DeviceInfo;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\Dto\PasswordRequestId;
use SixtyEightPublishers\ArchitectureBundle\Domain\Dto\EmailAddressInterface;
use SixtyEightPublishers\ArchitectureBundle\Domain\Event\AbstractDomainEvent;

final class PasswordChangeCanceled extends AbstractDomainEvent
{
	private PasswordRequestId $passwordRequestId;

	private DeviceInfo $finishedDeviceInfo;

	private EmailAddressInterface $emailAddress;

	/**
	 * @param \SixtyEightPublishers\ForgotPasswordBundle\Domain\Dto\PasswordRequestId   $passwordRequestId
	 * @param \SixtyEightPublishers\ForgotPasswordBundle\Domain\Dto\DeviceInfo          $finishedDeviceInfo
	 * @param \SixtyEightPublishers\ArchitectureBundle\Domain\Dto\EmailAddressInterface $emailAddress
	 *
	 * @return static
	 */
	public static function create(PasswordRequestId $passwordRequestId, DeviceInfo $finishedDeviceInfo, EmailAddressInterface $emailAddress): self
	{
		$event = self::occur($passwordRequestId->toString(), [
			'finished_ip_address' => $finishedDeviceInfo->ipAddress()->value(),
			'finished_user_agent' => $finishedDeviceInfo->userAgent()->value(),
			'email_address' => $emailAddress->value(),
		]);

		$event->passwordRequestId = $passwordRequestId;
		$event->finishedDeviceInfo = $finishedDeviceInfo;
		$event->emailAddress = $emailAddress;

		return $event;
	}

	/**
	 * @return \SixtyEightPublishers\ForgotPasswordBundle\Domain\Dto\PasswordRequestId
	 */
	public function passwordRequestId(): PasswordRequestId
	{
		return $this->passwordRequestId;
	}

	/**
	 * @return \SixtyEightPublishers\ForgotPasswordBundle\Domain\Dto\DeviceInfo
	 */
	public function finishedDeviceInfo(): DeviceInfo
	{
		return $this->finishedDeviceInfo;
	}

	/**
	 * @return \SixtyEightPublishers\ArchitectureBundle\Domain\Dto\EmailAddressInterface
	 */
	public function emailAddress(): EmailAddressInterface
	{
		return $this->emailAddress;
	}

	/**
	 * {@inheritDoc}
	 */
	protected function reconstituteState(array $parameters): void
	{
		$this->passwordRequestId = PasswordRequestId::fromUuid($this->aggregateId()->id());
		$this->finishedDeviceInfo = DeviceInfo::create(
			IpAddress::fromValue($parameters['finished_ip_address']),
			UserAgent::fromValue($parameters['finished_user_agent'])
		);
		$this->emailAddress = EmailAddress::fromValue($parameters['email_address']);
	}
}
