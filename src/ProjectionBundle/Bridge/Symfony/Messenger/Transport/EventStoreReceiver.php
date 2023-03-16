<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ProjectionBundle\Bridge\Symfony\Messenger\Transport;

use SixtyEightPublishers\ArchitectureBundle\Domain\Event\AbstractDomainEvent;
use SixtyEightPublishers\ArchitectureBundle\EventStore\EventCriteria;
use SixtyEightPublishers\ArchitectureBundle\EventStore\EventStoreException;
use SixtyEightPublishers\ArchitectureBundle\EventStore\EventStoreInterface;
use SixtyEightPublishers\ProjectionBundle\Projection\ProjectionInterface;
use SixtyEightPublishers\ProjectionBundle\ProjectionStore\ProjectionStoreException;
use SixtyEightPublishers\ProjectionBundle\ProjectionStore\ProjectionStoreInterface;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\LogicException;
use Symfony\Component\Messenger\Exception\TransportException;
use Symfony\Component\Messenger\Stamp\BusNameStamp;
use Symfony\Component\Messenger\Stamp\TransportMessageIdStamp;
use Symfony\Component\Messenger\Transport\Receiver\MessageCountAwareInterface;
use Symfony\Component\Messenger\Transport\Receiver\ReceiverInterface;
use Throwable;
use function assert;
use function is_subclass_of;
use function usort;

final class EventStoreReceiver implements ReceiverInterface, MessageCountAwareInterface
{
    private const MAX_RETRIES = 3;
    private const METADATA_AGGREGATE_CLASSNAME = '__aggregate_classname';

    private int $retryingSafetyCounter = 0;

    /** @var array<class-string, array<int, class-string>>|null */
    private ?array $eventClassnames = null;

    /**
     * @param class-string<ProjectionInterface> $projectionClassname
     */
    public function __construct(
        private readonly string $projectionClassname,
        private readonly EventStoreInterface $eventStore,
        private readonly ProjectionStoreInterface $projectionStore,
    ) {
        assert(is_subclass_of($this->projectionClassname, ProjectionInterface::class, true));
    }

    public function get(): iterable
    {
        $events = [];

        try {
            $lastPositions = $this->projectionStore->findLastPositions($this->projectionClassname);

            foreach ($lastPositions as $aggregateClassname => $position) {
                $criteria = EventCriteria::create($aggregateClassname)
                    ->withPositionGreaterThan($position)
                    ->withEventNames($this->getEventClassnames()[$aggregateClassname] ?? [])
                    ->withLowestPositionSorting()
                    ->withSize(20, null);

                foreach ($this->eventStore->find($criteria) as $event) {
                    $events[] = $event->withMetadata([self::METADATA_AGGREGATE_CLASSNAME => $aggregateClassname], true);
                }
            }
        } catch (EventStoreException|ProjectionStoreException $e) {
            if (++$this->retryingSafetyCounter >= self::MAX_RETRIES || !$e->retryable) {
                $this->retryingSafetyCounter = 0;

                throw new TransportException($e->getMessage(), 0, $e);
            }

            return;
        } catch (Throwable $e) {
            throw new TransportException($e->getMessage(), 0, $e);
        }

        usort(
            $events,
            static fn (AbstractDomainEvent $left, AbstractDomainEvent $right): int =>
            [$left->getMetadata()[self::METADATA_AGGREGATE_CLASSNAME], $left->getMetadata()[EventStoreInterface::METADATA_POSITION]]
            <=>
            [$right->getMetadata()[self::METADATA_AGGREGATE_CLASSNAME], $right->getMetadata()[EventStoreInterface::METADATA_POSITION]],
        );

        foreach ($events as $event) {
            assert($event instanceof AbstractDomainEvent);

            $envelope = new Envelope($event, [
                new BusNameStamp('projection_bus'),
                new TransportMessageIdStamp([
                    'projection' => $this->projectionClassname,
                    'aggregate_classname' => $event->getMetadata()[self::METADATA_AGGREGATE_CLASSNAME],
                    'position' => (string) $event->getMetadata()[EventStoreInterface::METADATA_POSITION],
                ]),
            ]);

            yield $envelope;
        }
    }

    public function ack(Envelope $envelope): void
    {
        $this->updateLastPosition($envelope);
    }

    public function reject(Envelope $envelope): void
    {
        $this->updateLastPosition($envelope);
    }

    public function getMessageCount(): int
    {
        try {
            $lastPositions = $this->projectionStore->findLastPositions($this->projectionClassname);
            $total = 0;

            foreach ($lastPositions as $aggregateClassname => $position) {
                $criteria = EventCriteria::create($aggregateClassname)
                    ->withPositionGreaterThan($position)
                    ->withEventNames($this->getEventClassnames()[$aggregateClassname] ?? []);

                $total += $this->eventStore->count($criteria);
            }

            return $total;
        } catch (ProjectionStoreException|EventStoreException $e) {
            throw new TransportException($e->getMessage(), 0, $e);
        }
    }

    private function updateLastPosition(Envelope $envelope): void
    {
        $idStamp = $envelope->last(TransportMessageIdStamp::class);

        if (!$idStamp instanceof TransportMessageIdStamp) {
            throw new LogicException('No TransportMessageIdStamp found on the Envelope.');
        }

        $id = $idStamp->getId();

        if (!isset($id['projection'], $id['aggregate_classname'], $id['position']) || $id['projection'] !== $this->projectionClassname) {
            throw new LogicException('Invalid TransportMessageIdStamp passed into the recepiver.');
        }

        try {
            $this->projectionStore->updateLastPosition($id['projection'], $id['aggregate_classname'], $id['position']);
        } catch (ProjectionStoreException $e) {
            throw new TransportException($e->getMessage(), 0, $e);
        }
    }

    /**
     * @return array<class-string, array<int, class-string>>
     */
    private function getEventClassnames(): array
    {
        if (null !== $this->eventClassnames) {
            return $this->eventClassnames;
        }

        $eventClassnames = [];

        foreach ($this->projectionClassname::defineEvents() as $eventDefinition) {
            if (!isset($eventClassnames[$eventDefinition->aggregateRootClassname])) {
                $eventClassnames[$eventDefinition->aggregateRootClassname] = [];
            }

            $eventClassnames[$eventDefinition->aggregateRootClassname][] = $eventDefinition->eventClassname;
        }

        return $this->eventClassnames = $eventClassnames;
    }
}
