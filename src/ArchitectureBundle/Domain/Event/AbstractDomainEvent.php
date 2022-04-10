<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Domain\Event;

use DateTimeZone;
use Ramsey\Uuid\Uuid;
use DateTimeImmutable;
use Ramsey\Uuid\UuidInterface;
use SixtyEightPublishers\ArchitectureBundle\Event\EventInterface;
use SixtyEightPublishers\ArchitectureBundle\Domain\Dto\AggregateId;

abstract class AbstractDomainEvent implements EventInterface
{
	protected const METADATA_AGGREGATE_ID = '_aggregate_id';
	protected const METADATA_AGGREGATE_VERSION = '_aggregate_version';

	protected string $eventName;

	protected UuidInterface $eventId;

	protected DateTimeImmutable $createdAt;

	protected array $parameters;

	protected array $metadata;

	/**
	 * @param string                     $eventName
	 * @param \Ramsey\Uuid\UuidInterface $eventId
	 * @param \DateTimeImmutable         $createdAt
	 * @param array                      $metadata
	 * @param array                      $parameters
	 *
	 * @return static
	 */
	public static function reconstitute(string $eventName, UuidInterface $eventId, DateTimeImmutable $createdAt, array $metadata, array $parameters): self
	{
		$event = new static();

		$event->eventName = $eventName;
		$event->eventId = $eventId;
		$event->createdAt = $createdAt;
		$event->parameters = $parameters;
		$event->metadata = $metadata;
		$event->reconstituteState($parameters);

		return $event;
	}

	/**
	 * @return string
	 */
	public function eventName(): string
	{
		return $this->eventName;
	}

	/**
	 * @return \Ramsey\Uuid\UuidInterface
	 */
	public function eventId(): UuidInterface
	{
		return $this->eventId;
	}

	/**
	 * @return \DateTimeImmutable
	 */
	public function createdAt(): DateTimeImmutable
	{
		return $this->createdAt;
	}

	/**
	 * @return array
	 */
	public function metadata(): array
	{
		return $this->metadata;
	}

	/**
	 * @return \SixtyEightPublishers\ArchitectureBundle\Domain\Dto\AggregateId
	 */
	public function aggregateId(): AggregateId
	{
		return AggregateId::fromString($this->metadata[self::METADATA_AGGREGATE_ID]);
	}

	/**
	 * @return int
	 */
	public function version(): int
	{
		return $this->metadata[self::METADATA_AGGREGATE_VERSION];
	}

	/**
	 * {@inheritDoc}
	 */
	public function parameters(): array
	{
		return $this->parameters;
	}

	/**
	 * {@inheritDoc}
	 */
	public function hasParam(string $name): bool
	{
		return array_key_exists($name, $this->parameters);
	}

	/**
	 * {@inheritDoc}
	 */
	public function getParam(string $name)
	{
		return $this->parameters[$name] ?? NULL;
	}

	/**
	 * @param string $name
	 * @param $value
	 *
	 * @return $this
	 */
	public function withParam(string $name, $value): self
	{
		$event = clone $this;
		$event->metadata[$name] = $value;

		return $event;
	}

	/**
	 * @param int $version
	 *
	 * @return $this
	 */
	public function withVersion(int $version): self
	{
		$event = clone $this;
		$event->metadata[self::METADATA_AGGREGATE_VERSION] = $version;

		return $event;
	}

	/**
	 * @param array $metadata
	 * @param bool  $merge
	 *
	 * @return $this
	 */
	public function withMetadata(array $metadata, bool $merge = FALSE): self
	{
		$event = clone $this;
		$event->metadata = $merge ? array_merge($event->metadata(), $metadata) : $metadata;

		return $event;
	}

	/**
	 * @return array
	 */
	public function toArray(): array
	{
		return [
			'event_name' => $this->eventName,
			'event_id' => $this->eventId->toString(),
			'created_at' => $this->createdAt(),
			'parameters' => $this->parameters(),
			'metadata' => $this->metadata,
		];
	}

	/**
	 * @param string $aggregateId
	 * @param array  $parameters
	 *
	 * @return static
	 * @noinspection PhpDocMissingThrowsInspection
	 */
	protected static function occur(string $aggregateId, array $parameters = []): self
	{
		$event = new static();

		$event->eventName = static::class;
		$event->eventId = Uuid::uuid4();
		/** @noinspection PhpUnhandledExceptionInspection */
		$event->createdAt = new DateTimeImmutable('now', new DateTimeZone('UTC'));
		$event->parameters = $parameters;

		$event->metadata = [
			self::METADATA_AGGREGATE_ID => $aggregateId,
			self::METADATA_AGGREGATE_VERSION => 1, // initial
		];

		return $event;
	}

	/**
	 * @param array $parameters
	 *
	 * @return void
	 */
	protected function reconstituteState(array $parameters): void
	{
	}
}
