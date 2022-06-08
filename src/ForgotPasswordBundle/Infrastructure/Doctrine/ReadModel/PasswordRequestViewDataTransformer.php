<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ForgotPasswordBundle\Infrastructure\Doctrine\ReadModel;

use SixtyEightPublishers\ForgotPasswordBundle\Domain\ValueObject\DeviceInfo;
use SixtyEightPublishers\ArchitectureBundle\ReadModel\View\ViewDataInterface;
use SixtyEightPublishers\ForgotPasswordBundle\ReadModel\View\PasswordRequestView;
use SixtyEightPublishers\ArchitectureBundle\ReadModel\View\ViewDataTransformerInterface;
use SixtyEightPublishers\ArchitectureBundle\Infrastructure\Doctrine\ReadModel\DoctrineViewData;

final class PasswordRequestViewDataTransformer implements ViewDataTransformerInterface
{
	/**
	 * {@inheritDoc}
	 */
	public function canTransform(string $viewClassname, ViewDataInterface $viewData): bool
	{
		return is_a($viewClassname, PasswordRequestView::class, TRUE) && $viewData instanceof DoctrineViewData;
	}

	/**
	 * {@inheritDoc}
	 */
	public function transform(ViewDataInterface $viewData): ViewDataInterface
	{
		return $viewData
			->with('requestDeviceInfo', DeviceInfo::create($viewData->get('requestDeviceInfo.ipAddress'), $viewData->get('requestDeviceInfo.userAgent')))
			->with('finishedDeviceInfo', DeviceInfo::create($viewData->get('finishedDeviceInfo.ipAddress'), $viewData->get('finishedDeviceInfo.userAgent')))
			->without(
				'requestDeviceInfo.ipAddress',
				'requestDeviceInfo.userAgent',
				'finishedDeviceInfo.ipAddress',
				'finishedDeviceInfo.userAgent'
			);
	}
}
