<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Domain;

use DateTimeImmutable;
use SixtyEightPublishers\ArchitectureBundle\Domain\Event\AbstractDomainEvent;
use SixtyEightPublishers\ArchitectureBundle\Domain\Event\AggregateDeleted;
use SixtyEightPublishers\ArchitectureBundle\Domain\Exception\UnableToRecordEventOnDeletedAggregateException;

trait DeletableAggregateRootTrait
{
    use AggregateRootTrait {
        recordThat as _recordThat;
    }

    protected ?DateTimeImmutable $deletedAt = null;

    protected function recordThat(AbstractDomainEvent $event, bool $apply = true): void
    {
        if ($this->isDeleted()) {
            throw UnableToRecordEventOnDeletedAggregateException::create(static::class, $this->getAggregateId());
        }

        $this->_recordThat($event, $apply);
    }

    public function isDeleted(): bool
    {
        return null !== $this->deletedAt;
    }

    public function delete(): void
    {
        if (!$this->deletedAt) {
            $this->recordThat(AggregateDeleted::create(static::class, $this->getAggregateId()));
        }
    }

    protected function whenAggregateDeleted(AggregateDeleted $event): void
    {
        $this->deletedAt = $event->getCreatedAt();
    }
}
