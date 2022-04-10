<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\Infrastructure\Doctrine\DbalType;

use SixtyEightPublishers\UserBundle\Domain\Dto\Roles;
use SixtyEightPublishers\ArchitectureBundle\Infrastructure\Doctrine\DbalType\AbstractValueObjectSetType;

final class RolesType extends AbstractValueObjectSetType
{
	protected string $dtoClassname = Roles::class;
}
