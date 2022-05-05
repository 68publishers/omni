<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\Infrastructure\Doctrine\DbalType;

use SixtyEightPublishers\UserBundle\Domain\ValueObject\Roles;
use SixtyEightPublishers\ArchitectureBundle\Infrastructure\Doctrine\DbalType\AbstractValueObjectSetType;

final class RolesType extends AbstractValueObjectSetType
{
	protected string $valueObjectClassname = Roles::class;
}
