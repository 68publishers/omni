<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\Infrastructure\Doctrine\DbalType;

use SixtyEightPublishers\UserBundle\Domain\Dto\EmailAddress;
use SixtyEightPublishers\ArchitectureBundle\Infrastructure\Doctrine\DbalType\AbstractStringValueObjectType;

final class EmailAddressType extends AbstractStringValueObjectType
{
	protected string $dtoClassname = EmailAddress::class;
}
