<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\Infrastructure\Doctrine\DbalType;

use SixtyEightPublishers\UserBundle\Domain\Dto\HashedPassword;
use SixtyEightPublishers\ArchitectureBundle\Infrastructure\Doctrine\DbalType\AbstractStringValueObjectType;

final class HashedPasswordType extends AbstractStringValueObjectType
{
	protected string $dtoClassname = HashedPassword::class;
}
