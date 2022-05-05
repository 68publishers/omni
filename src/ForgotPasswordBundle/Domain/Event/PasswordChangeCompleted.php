<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ForgotPasswordBundle\Domain\Event;

use BadMethodCallException;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\ValueObject\IpAddress;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\ValueObject\UserAgent;
use SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\EmailAddress;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\ValueObject\DeviceInfo;
use SixtyEightPublishers\ArchitectureBundle\Domain\Event\AbstractDomainEvent;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\ValueObject\PasswordRequestId;
use SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\EmailAddressInterface;

final class PasswordChangeCompleted extends AbstractDomainEvent
{
	private PasswordRequestId $passwordRequestId;

	private DeviceInfo $finishedDeviceInfo;

	private EmailAddressInterface $emailAddress;

	private ?string $password = NULL;

	/**
	 * @param \SixtyEightPublishers\ForgotPasswordBundle\Domain\ValueObject\PasswordRequestId   $passwordRequestId
	 * @param \SixtyEightPublishers\ForgotPasswordBundle\Domain\ValueObject\DeviceInfo          $finishedDeviceInfo
	 * @param \SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\EmailAddressInterface $emailAddress
	 * @param string                                                                            $password
	 *
	 * @return static
	 */
	public static function create(PasswordRequestId $passwordRequestId, DeviceInfo $finishedDeviceInfo, EmailAddressInterface $emailAddress, string $password): self
	{
		$event = self::occur($passwordRequestId->toString(), [
			'finished_ip_address' => $finishedDeviceInfo->ipAddress()->value(),
			'finished_user_agent' => $finishedDeviceInfo->userAgent()->value(),
			'email_address' => $emailAddress->value(),
		]);

		$event->passwordRequestId = $passwordRequestId;
		$event->finishedDeviceInfo = $finishedDeviceInfo;
		$event->emailAddress = $emailAddress;
		$event->password = $password;

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
	 * @return \SixtyEightPublishers\ForgotPasswordBundle\Domain\ValueObject\DeviceInfo
	 */
	public function finishedDeviceInfo(): DeviceInfo
	{
		return $this->finishedDeviceInfo;
	}

	/**
	 * @return \SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\EmailAddressInterface
	 */
	public function emailAddress(): EmailAddressInterface
	{
		return $this->emailAddress;
	}

	/**
	 * @return string
	 */
	public function password(): string
	{
		if (NULL === $this->password) {
			throw new BadMethodCallException('Password is missing because raw passwords can not be stored in the event stream.');
		}

		return $this->password;
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
