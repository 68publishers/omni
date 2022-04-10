<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Infrastructure\Doctrine\DbalType;

use SixtyEightPublishers\ArchitectureBundle\Domain\Dto\AggregateId;

final class AggregateIdType extends AbstractUuidIdentityType
{
	protected string $dtoClassname = AggregateId::class;
}
