<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ForgotPasswordBundle\Domain\Event;

use SixtyEightPublishers\ForgotPasswordBundle\Domain\Dto\IpAddress;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\Dto\UserAgent;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\Dto\DeviceInfo;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\Dto\PasswordRequestId;
use SixtyEightPublishers\ArchitectureBundle\Domain\Event\AbstractDomainEvent;

final class PasswordChangeCanceled extends AbstractDomainEvent
{
	private PasswordRequestId $passwordRequestId;

	private DeviceInfo $finishedDeviceInfo;

	/**
	 * @param \SixtyEightPublishers\ForgotPasswordBundle\Domain\Dto\PasswordRequestId $passwordRequestId
	 * @param \SixtyEightPublishers\ForgotPasswordBundle\Domain\Dto\DeviceInfo        $finishedDeviceInfo
	 *
	 * @return static
	 */
	public static function create(PasswordRequestId $passwordRequestId, DeviceInfo $finishedDeviceInfo): self
	{
		$event = self::occur($passwordRequestId->toString(), [
			'finished_ip_address' => $finishedDeviceInfo->ipAddress()->value(),
			'finished_user_agent' => $finishedDeviceInfo->userAgent()->value(),
		]);

		$event->passwordRequestId = $passwordRequestId;
		$event->finishedDeviceInfo = $finishedDeviceInfo;

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
	 * {@inheritDoc}
	 */
	protected function reconstituteState(array $parameters): void
	{
		$this->passwordRequestId = PasswordRequestId::fromUuid($this->aggregateId()->id());
		$this->finishedDeviceInfo = DeviceInfo::create(
			IpAddress::fromValue($parameters['finished_ip_address']),
			UserAgent::fromValue($parameters['finished_user_agent'])
		);
	}
}
