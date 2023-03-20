<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\EventStore;

use Exception;
use SixtyEightPublishers\ArchitectureBundle\Domain\Event\AbstractDomainEvent;
use SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\EventId;
use Throwable;
use function sprintf;

final class EventStoreException extends Exception
{
    public function __construct(
        string $message,
        public readonly bool $retryable,
        ?Throwable $previous = null,
    ) {
        parent::__construct($message, 0, $previous);
    }

    /**
     * @param class-string $aggregateRootClassname
     */
    public static function unableToStoreEvent(string $aggregateRootClassname, AbstractDomainEvent $event, bool $retryable, ?Throwable $previous = null): self
    {
        return new self(sprintf(
            'Unable to store an event %s [%s] for an aggregate %s [%s]',
            $event->getEventName(),
            $event->getEventId()->toNative(),
            $aggregateRootClassname,
            $event->getAggregateId()->toNative(),
        ), $retryable, $previous);
    }

    /**
     * @param class-string $aggregateRootClassname
     */
    public static function unableToGetEvent(string $aggregateRootClassname, EventId $eventId, bool $retryable, ?Throwable $previous = null): self
    {
        return new self(sprintf(
            'Unable to get an event with ID %s for an aggregate of type %s',
            $eventId->toNative(),
            $aggregateRootClassname,
        ), $retryable, $previous);
    }

    /**
     * @param class-string $aggregateRootClassname
     */
    public static function unableToFindEvents(string $aggregateRootClassname, bool $retryable, ?Throwable $previous = null): self
    {
        return new self(sprintf(
            'Unable to find events for an aggregate of type %s',
            $aggregateRootClassname,
        ), $retryable, $previous);
    }

    /**
     * @param class-string $aggregateRootClassname
     */
    public static function unableToCountEvents(string $aggregateRootClassname, bool $retryable, ?Throwable $previous = null): self
    {
        return new self(sprintf(
            'Unable to count events for an aggregate of type %s',
            $aggregateRootClassname,
        ), $retryable, $previous);
    }

    public static function unableToResolveEventStore(string $aggregateRootClassname, string $eventStoreName): self
    {
        return new self(sprintf(
            'Unable to resolve a event store with the name %s for an aggregate of the type %s',
            $eventStoreName,
            $aggregateRootClassname,
        ), false);
    }

    public static function of(Throwable $error, bool $retryable): self
    {
        return new self(sprintf(
            'EventStore error: %s',
            $error->getMessage(),
        ), $retryable, $error);
    }
}
