<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ForgotPasswordBundle\Infrastructure\Doctrine\DbalType;

use SixtyEightPublishers\ForgotPasswordBundle\Domain\Dto\PasswordRequestId;
use SixtyEightPublishers\ArchitectureBundle\Infrastructure\Doctrine\DbalType\AbstractUuidIdentityType;

final class PasswordRequestIdType extends AbstractUuidIdentityType
{
	protected string $dtoClassname = PasswordRequestId::class;
}
