<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Infrastructure\Doctrine\DbalType;

use SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\EventId;

final class EventIdType extends AbstractUuidIdentityType
{
	protected string $valueObjectClassname = EventId::class;
}
