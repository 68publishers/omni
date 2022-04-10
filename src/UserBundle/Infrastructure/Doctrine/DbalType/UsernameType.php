<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\Infrastructure\Doctrine\DbalType;

use SixtyEightPublishers\UserBundle\Domain\Dto\Username;
use SixtyEightPublishers\ArchitectureBundle\Infrastructure\Doctrine\DbalType\AbstractStringValueObjectType;

final class UsernameType extends AbstractStringValueObjectType
{
	protected string $dtoClassname = Username::class;
}
