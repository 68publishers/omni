<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ForgotPasswordBundle\Domain\ValueObject;

use SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\ComparableValueObjectInterface;

final class DeviceInfo implements ComparableValueObjectInterface
{
	private IpAddressInterface $ipAddress;

	private UserAgent $userAgent;

	private function __construct()
	{
	}

	/**
	 * @param \SixtyEightPublishers\ForgotPasswordBundle\Domain\ValueObject\IpAddressInterface $ipAddress
	 * @param \SixtyEightPublishers\ForgotPasswordBundle\Domain\ValueObject\UserAgent          $userAgent
	 *
	 * @return static
	 */
	public static function create(IpAddressInterface $ipAddress, UserAgent $userAgent): self
	{
		$deviceInfo = new self();
		$deviceInfo->ipAddress = $ipAddress;
		$deviceInfo->userAgent = $userAgent;

		return $deviceInfo;
	}

	/**
	 * @return \SixtyEightPublishers\ForgotPasswordBundle\Domain\ValueObject\IpAddressInterface
	 */
	public function ipAddress(): IpAddressInterface
	{
		return $this->ipAddress;
	}

	/**
	 * @return \SixtyEightPublishers\ForgotPasswordBundle\Domain\ValueObject\UserAgent
	 */
	public function userAgent(): UserAgent
	{
		return $this->userAgent;
	}

	/**
	 * {@inheritDoc}
	 */
	public function equals(ComparableValueObjectInterface $valueObject): bool
	{
		return $valueObject instanceof self
			&& $this->ipAddress()->equals($valueObject->ipAddress())
			&& $this->userAgent()->equals($valueObject->userAgent());
	}
}
