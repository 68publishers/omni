<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ForgotPasswordBundle\Infrastructure\Doctrine\DbalType;

use SixtyEightPublishers\ForgotPasswordBundle\Domain\Dto\IpAddress;
use SixtyEightPublishers\ArchitectureBundle\Infrastructure\Doctrine\DbalType\AbstractStringValueObjectType;

final class IpAddressType extends AbstractStringValueObjectType
{
	protected string $dtoClassname = IpAddress::class;
}
