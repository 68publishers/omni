<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Domain\Event;

use SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\AggregateId;

final class AggregateDeleted extends AbstractDomainEvent
{
    public static function create(string $aggregateClassname, AggregateId $aggregateId): self
    {
        return self::occur($aggregateId->toNative(), [
            'aggregate_class_name' => $aggregateClassname,
        ]);
    }

    public function getAggregateClassname(): string
    {
        return $this->parameters['aggregate_class_name'];
    }
}
