<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ForgotPasswordBundle\Domain\Dto;

use SixtyEightPublishers\ArchitectureBundle\Domain\Dto\ComparableValueObjectInterface;

final class DeviceInfo implements ComparableValueObjectInterface
{
	private IpAddressInterface $ipAddress;

	private UserAgent $userAgent;

	private function __construct()
	{
	}

	/**
	 * @param \SixtyEightPublishers\ForgotPasswordBundle\Domain\Dto\IpAddressInterface $ipAddress
	 * @param \SixtyEightPublishers\ForgotPasswordBundle\Domain\Dto\UserAgent          $userAgent
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
	 * @return \SixtyEightPublishers\ForgotPasswordBundle\Domain\Dto\IpAddressInterface
	 */
	public function ipAddress(): IpAddressInterface
	{
		return $this->ipAddress;
	}

	/**
	 * @return \SixtyEightPublishers\ForgotPasswordBundle\Domain\Dto\UserAgent
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
