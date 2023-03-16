<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\EventStore;

use DateTimeImmutable;
use SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\AggregateId;

final class EventCriteria
{
    public const SORTING_FROM_OLDEST = 'oldest';
    public const SORTING_FROM_NEWEST = 'newest';
    public const SORTING_FROM_LOWEST_POSITION= 'lowest_position';
    public const SORTING_FROM_HIGHEST_POSITION= 'highest_position';

    /** @var class-string */
    private string $aggregateRootClassname;

    private ?AggregateId $aggregateId = null;

    private ?DateTimeImmutable $createdBefore = null;

    private ?DateTimeImmutable $createdAfter = null;

    private ?string $positionGreaterThan = null;

    private ?string $positionLessThan = null;

    /** @var array<class-string> */
    private array $eventNames = [];

    private string $sorting = self::SORTING_FROM_NEWEST;

    private ?int $limit = null;

    private ?int $offset = null;

    private function __construct() {}

    /**
     * @param class-string $aggregateRootClassname
     */
    public static function create(string $aggregateRootClassname): self
    {
        $criteria = new self();
        $criteria->aggregateRootClassname = $aggregateRootClassname;

        return $criteria;
    }

    public function withAggregateId(AggregateId $aggregateId): self
    {
        $criteria = clone $this;
        $criteria->aggregateId = $aggregateId;

        return $criteria;
    }

    public function withCreatedBefore(DateTimeImmutable $createdBefore): self
    {
        $criteria = clone $this;
        $criteria->createdBefore = $createdBefore;

        return $criteria;
    }

    public function withCreatedAfter(DateTimeImmutable $createdAfter): self
    {
        $criteria = clone $this;
        $criteria->createdAfter = $createdAfter;

        return $criteria;
    }

    public function withPositionGreaterThan(string $position): self
    {
        $criteria = clone $this;
        $criteria->positionGreaterThan = $position;

        return $criteria;
    }

    public function withPositionLessThan(string $position): self
    {
        $criteria = clone $this;
        $criteria->positionLessThan = $position;

        return $criteria;
    }

    /**
     * @param array<class-string> $eventNames
     */
    public function withEventNames(array $eventNames): self
    {
        $criteria = clone $this;
        $criteria->eventNames = $eventNames;

        return $criteria;
    }

    public function withNewestSorting(): self
    {
        $criteria = clone $this;
        $criteria->sorting = self::SORTING_FROM_NEWEST;

        return $criteria;
    }

    public function withOldestSorting(): self
    {
        $criteria = clone $this;
        $criteria->sorting = self::SORTING_FROM_OLDEST;

        return $criteria;
    }

    public function withLowestPositionSorting(): self
    {
        $criteria = clone $this;
        $criteria->sorting = self::SORTING_FROM_LOWEST_POSITION;

        return $criteria;
    }

    public function withHighestPositionSorting(): self
    {
        $criteria = clone $this;
        $criteria->sorting = self::SORTING_FROM_HIGHEST_POSITION;

        return $criteria;
    }

    public function withSize(?int $limit, ?int $offset): self
    {
        $criteria = clone $this;
        $criteria->limit = $limit;
        $criteria->offset = $offset;

        return $criteria;
    }

    /**
     * @return class-string
     */
    public function getAggregateRootClassname(): string
    {
        return $this->aggregateRootClassname;
    }

    public function getAggregateId(): ?AggregateId
    {
        return $this->aggregateId;
    }

    public function getCreatedBefore(): ?DateTimeImmutable
    {
        return $this->createdBefore;
    }

    public function getCreatedAfter(): ?DateTimeImmutable
    {
        return $this->createdAfter;
    }

    public function getPositionGreaterThan(): ?string
    {
        return $this->positionGreaterThan;
    }

    public function getPositionLessThan(): ?string
    {
        return $this->positionLessThan;
    }

    /**
     * @return array<class-string>
     */
    public function getEventNames(): array
    {
        return $this->eventNames;
    }

    public function getSorting(): string
    {
        return $this->sorting;
    }

    public function getLimit(): ?int
    {
        return $this->limit;
    }

    public function getOffset(): ?int
    {
        return $this->offset;
    }
}
