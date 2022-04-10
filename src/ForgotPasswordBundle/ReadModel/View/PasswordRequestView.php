<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ForgotPasswordBundle\ReadModel\View;

use DateTimeImmutable;
use SixtyEightPublishers\UserBundle\Domain\Dto\UserId;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\Dto\Status;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\Dto\DeviceInfo;
use SixtyEightPublishers\ArchitectureBundle\ReadModel\View\AbstractView;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\Dto\PasswordRequestId;

/**
 * @property-read PasswordRequestId $id
 * @property-read UserId $userId
 * @property-read Status $status
 * @property-read DateTimeImmutable $requestedAt
 * @property-read DateTimeImmutable $expiredAt
 * @property-read DateTimeImmutable|NULL $finishedAt
 * @property-read DeviceInfo $requestDeviceInfo
 * @property-read DeviceInfo $finishedDeviceInfo
 */
class PasswordRequestView extends AbstractView
{
}
