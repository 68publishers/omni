<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\EventStore;

use Exception;
use Throwable;
use SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\EventId;
use SixtyEightPublishers\ArchitectureBundle\Domain\Event\AbstractDomainEvent;

final class EventStoreException extends Exception
{
	private bool $retryable;

	private function __construct(string $message, bool $retryable, ?Throwable $previous = NULL)
	{
		parent::__construct($message, 0, $previous);

		$this->retryable = $retryable;
	}

	public static function unableToStoreEvent(string $aggregateRootClassname, AbstractDomainEvent $event, bool $retryable, ?Throwable $previous = NULL): self
	{
		return new self(sprintf(
			'Unable to store an event %s [%s] for an aggregate %s [%s]',
			$event->eventName(),
			$event->eventId()->toString(),
			$aggregateRootClassname,
			$event->aggregateId()->toString()
		), $retryable, $previous);
	}

	public static function unableToGetEvent(string $aggregateRootClassname, EventId $eventId, bool $retryable, ?Throwable $previous = NULL): self
	{
		return new self(sprintf(
			'Unable to get an event with ID %s for an aggregate of type %s',
			$eventId->toString(),
			$aggregateRootClassname,
		), $retryable, $previous);
	}

	public static function unableToFindEvents(string $aggregateRootClassname, bool $retryable, ?Throwable $previous = NULL): self
	{
		return new self(sprintf(
			'Unable to find events for an aggregate of type %s',
			$aggregateRootClassname,
		), $retryable, $previous);
	}

	public static function unableToCountEvents(string $aggregateRootClassname, bool $retryable, ?Throwable $previous = NULL): self
	{
		return new self(sprintf(
			'Unable to count events for an aggregate of type %s',
			$aggregateRootClassname,
		), $retryable, $previous);
	}

	public static function of(Throwable $error, bool $retryable): self
	{
		return new self(sprintf(
			'EventStore error: %s',
			$error->getMessage(),
		), $retryable, $error);
	}

	public function retryable(): bool
	{
		return $this->retryable;
	}
}
