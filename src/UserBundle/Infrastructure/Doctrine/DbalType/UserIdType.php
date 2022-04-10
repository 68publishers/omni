<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\Infrastructure\Doctrine\DbalType;

use SixtyEightPublishers\UserBundle\Domain\Dto\UserId;
use SixtyEightPublishers\ArchitectureBundle\Infrastructure\Doctrine\DbalType\AbstractUuidIdentityType;

final class UserIdType extends AbstractUuidIdentityType
{
	protected string $dtoClassname = UserId::class;
}
