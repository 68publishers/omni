<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ForgotPasswordBundle\Infrastructure\Doctrine\QueryHandler;

use SixtyEightPublishers\ForgotPasswordBundle\Domain\Dto\DeviceInfo;
use SixtyEightPublishers\ForgotPasswordBundle\ReadModel\View\PasswordRequestView;

final class ViewFactory
{
	private function __construct()
	{
	}

	/**
	 * @param array $data
	 *
	 * @return \SixtyEightPublishers\ForgotPasswordBundle\ReadModel\View\PasswordRequestView
	 */
	public static function createPasswordRequestView(array $data): PasswordRequestView
	{
		$data['requestDeviceInfo'] = DeviceInfo::create($data['requestDeviceInfo.ipAddress'], $data['requestDeviceInfo.userAgent']);
		$data['finishedDeviceInfo'] = DeviceInfo::create($data['finishedDeviceInfo.ipAddress'], $data['finishedDeviceInfo.userAgent']);

		return PasswordRequestView::fromArray($data);
	}
}
