<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Domain\Event;

use BackedEnum;
use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\AggregateIdInterface;
use SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\EventId;
use SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\ValueObjectInterface;
use SixtyEightPublishers\ArchitectureBundle\Event\EventInterface;
use function array_map;
use function array_merge;

abstract class AbstractDomainEvent implements EventInterface
{
    protected const METADATA_AGGREGATE_ID = '_aggregate_id';
    protected const METADATA_AGGREGATE_VERSION = '_aggregate_version';

    /** @var class-string $eventName */
    protected string $eventName;

    protected EventId $eventId;

    protected DateTimeImmutable $createdAt;

    /** @var array<string, mixed> */
    protected array $parameters;

    /** @var array<string, mixed> */
    protected array $metadata;

    private function __construct() {}

    /**
     * @param class-string         $eventName
     * @param array<string, mixed> $metadata
     * @param array<string, mixed> $parameters
     */
    public static function reconstitute(
        string $eventName,
        EventId $eventId,
        DateTimeImmutable $createdAt,
        array $metadata,
        array $parameters,
    ): static {
        $event = new static(); // @phpstan-ignore-line

        $event->eventName = $eventName;
        $event->eventId = $eventId;
        $event->createdAt = $createdAt;
        $event->parameters = $parameters;
        $event->metadata = $metadata;

        return $event;
    }

    abstract public function getAggregateId(): AggregateIdInterface;

    /**
     * @return class-string
     */
    public function getEventName(): string
    {
        return $this->eventName;
    }

    public function getEventId(): EventId
    {
        return $this->eventId;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    /**
     * @return array<string, mixed>
     */
    public function getMetadata(): array
    {
        return $this->metadata;
    }

    public function getVersion(): int
    {
        return $this->metadata[self::METADATA_AGGREGATE_VERSION];
    }

    /**
     * @return array<string, mixed>
     */
    public function getParameters(): array
    {
        return $this->parameters;
    }

    public function withVersion(int $version): static
    {
        $event = clone $this;
        $event->metadata[self::METADATA_AGGREGATE_VERSION] = $version;

        return $event;
    }

    /**
     * @param array<string, mixed> $metadata
     */
    public function withMetadata(array $metadata, bool $merge = false): static
    {
        $event = clone $this;
        $event->metadata = $merge ? array_merge($event->getMetadata(), $metadata) : $metadata;

        return $event;
    }

    /**
     * @return array{event_name: class-string, event_id: string, created_at: DateTimeImmutable, parameters: array<string, mixed>, metadata: array<string, mixed>}
     */
    public function toArray(): array
    {
        return [
            'event_name' => $this->eventName,
            'event_id' => $this->eventId->toNative(),
            'created_at' => $this->getCreatedAt(),
            'parameters' => $this->getParameters(),
            'metadata' => $this->metadata,
        ];
    }

    protected function getNativeAggregatedId(): mixed
    {
        return $this->metadata[self::METADATA_AGGREGATE_ID];
    }

    /**
     * @param array<string, mixed> $parameters
     *
     * @noinspection PhpDocMissingThrowsInspection
     */
    protected static function occur(AggregateIdInterface $aggregateId, array $parameters = []): static
    {
        $event = new static();  // @phpstan-ignore-line

        $event->eventName = static::class;
        $event->eventId = EventId::new();
        /** @noinspection PhpUnhandledExceptionInspection */
        $event->createdAt = new DateTimeImmutable('now', new DateTimeZone('UTC'));
        $event->parameters = array_map(
            static function (mixed $value): mixed {
                if ($value instanceof ValueObjectInterface) {
                    return $value->toNative();
                }

                if ($value instanceof BackedEnum) {
                    return $value->value;
                }

                if ($value instanceof DateTimeInterface) {
                    return $value->format(DateTimeInterface::ATOM);
                }

                if ($value instanceof DateTimeZone) {
                    return $value->getName();
                }

                return $value;
            },
            $parameters,
        );

        $event->metadata = [
            self::METADATA_AGGREGATE_ID => $aggregateId->toNative(),
            self::METADATA_AGGREGATE_VERSION => 1, // initial
        ];

        return $event;
    }
}
