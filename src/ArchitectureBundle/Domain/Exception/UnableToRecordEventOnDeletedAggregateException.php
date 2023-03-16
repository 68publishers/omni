<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Domain\Exception;

use DomainException;
use SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\AggregateId;
use function sprintf;

final class UnableToRecordEventOnDeletedAggregateException extends DomainException
{
    /**
     * @param class-string $aggregateClassname
     */
    private function __construct(
        string $message,
        public readonly string $aggregateClassname,
        public readonly AggregateId $aggregateId,
    ) {
        parent::__construct($message);
    }

    /**
     * @param class-string $aggregateClassname
     */
    public static function create(string $aggregateClassname, AggregateId $aggregateId): self
    {
        return new self(sprintf(
            'Unable to record event on deleted aggregate %s of type %s',
            $aggregateId->toNative(),
            $aggregateClassname,
        ), $aggregateClassname, $aggregateId);
    }
}
