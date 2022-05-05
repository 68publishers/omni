<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ForgotPasswordBundle\ReadModel\View;

use Exception;
use DateTimeZone;
use DateTimeImmutable;
use DateTimeInterface;
use SixtyEightPublishers\ArchitectureBundle\ReadModel\View\AbstractView;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\ValueObject\Status;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\ValueObject\DeviceInfo;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\ValueObject\PasswordRequestId;
use SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\EmailAddressInterface;

class PasswordRequestView extends AbstractView
{
	public PasswordRequestId $id;

	public EmailAddressInterface $emailAddress;

	public Status $status;

	public DateTimeImmutable $requestedAt;

	public DateTimeImmutable $expiredAt;

	public ?DateTimeImmutable $finishedAt;

	public DeviceInfo $requestDeviceInfo;

	public DeviceInfo $finishedDeviceInfo;

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
	 * @return array
	 */
	public function jsonSerialize(): array
	{
		return [
			'id' => $this->id->toString(),
			'emailAddress' => $this->emailAddress->value(),
			'status' => $this->status->value(),
			'requestedAt' => $this->requestedAt->format(DateTimeInterface::ATOM),
			'expiredAt' => $this->expiredAt->format(DateTimeInterface::ATOM),
			'finishedAt' => $this->finishedAt->format(DateTimeInterface::ATOM),
			'expired' => $this->expired(),
			'requestDeviceInfo' => [
				'userAgent' => $this->requestDeviceInfo->userAgent(),
				'ipAddress' => $this->requestDeviceInfo->ipAddress(),
			],
			'finishedDeviceInfo' => [
				'userAgent' => $this->finishedDeviceInfo->userAgent(),
				'ipAddress' => $this->finishedDeviceInfo->ipAddress(),
			],
		];
	}
}
