<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ForgotPasswordBundle\Domain\Event;

use DateTimeImmutable;
use DateTimeInterface;
use SixtyEightPublishers\UserBundle\Domain\Dto\UserId;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\Dto\IpAddress;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\Dto\UserAgent;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\Dto\DeviceInfo;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\Dto\PasswordRequestId;
use SixtyEightPublishers\ArchitectureBundle\Domain\Event\AbstractDomainEvent;

final class PasswordChangeRequested extends AbstractDomainEvent
{
	private PasswordRequestId $passwordRequestId;

	private UserId $userId;

	private DeviceInfo $requestDeviceInfo;

	private DateTimeImmutable $expiredAt;

	/**
	 * @param \SixtyEightPublishers\ForgotPasswordBundle\Domain\Dto\PasswordRequestId $passwordRequestId
	 * @param \SixtyEightPublishers\UserBundle\Domain\Dto\UserId                      $userId
	 * @param \SixtyEightPublishers\ForgotPasswordBundle\Domain\Dto\DeviceInfo        $requestDeviceInfo
	 * @param \DateTimeImmutable                                                      $expiredAt
	 *
	 * @return static
	 */
	public static function create(PasswordRequestId $passwordRequestId, UserId $userId, DeviceInfo $requestDeviceInfo, DateTimeImmutable $expiredAt): self
	{
		$event = self::occur($passwordRequestId->toString(), [
			'user_id' => $userId->toString(),
			'request_ip_address' => $requestDeviceInfo->ipAddress()->value(),
			'request_user_agent' => $requestDeviceInfo->userAgent()->value(),
			'expired_at' => $expiredAt->format(DateTimeInterface::ATOM),
		]);

		$event->passwordRequestId = $passwordRequestId;
		$event->userId = $userId;
		$event->requestDeviceInfo = $requestDeviceInfo;
		$event->expiredAt = $expiredAt;

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
	 * @return \SixtyEightPublishers\UserBundle\Domain\Dto\UserId
	 */
	public function userId(): UserId
	{
		return $this->userId;
	}

	/**
	 * @return \SixtyEightPublishers\ForgotPasswordBundle\Domain\Dto\DeviceInfo
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
		$this->userId = UserId::fromString($parameters['user_id']);
		$this->requestDeviceInfo = DeviceInfo::create(
			IpAddress::fromValue($parameters['request_ip_address']),
			UserAgent::fromValue($parameters['request_user_agent'])
		);
		$this->expiredAt = new DateTimeImmutable($parameters['expired_at']);
	}
}
