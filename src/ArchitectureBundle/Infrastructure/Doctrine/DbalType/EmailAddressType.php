<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Infrastructure\Doctrine\DbalType;

use SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\EmailAddress;

final class EmailAddressType extends AbstractStringValueObjectType
{
	protected string $valueObjectClassname = EmailAddress::class;
}
