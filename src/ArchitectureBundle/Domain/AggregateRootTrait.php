<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Domain;

use RuntimeException;
use SixtyEightPublishers\ArchitectureBundle\Domain\Event\AbstractDomainEvent;
use SixtyEightPublishers\ArchitectureBundle\Domain\ValueObject\AggregateIdInterface;
use function array_slice;
use function explode;
use function implode;
use function method_exists;
use function sprintf;

trait AggregateRootTrait
{
    protected int $version = 0;

    /** @var array<AbstractDomainEvent> */
    protected array $recordedEvents = [];

    abstract public function getAggregateId(): AggregateIdInterface;

    public function getVersion(): int
    {
        return $this->version;
    }

    /**
     * @return array<AbstractDomainEvent>
     */
    public function popRecordedEvents(): array
    {
        $pendingEvents = $this->recordedEvents;
        $this->recordedEvents = [];

        return $pendingEvents;
    }

    protected function apply(AbstractDomainEvent $event): void
    {
        $handler = $this->determineEventHandlerMethodFor($event);

        if (!method_exists($this, $handler)) {
            throw new RuntimeException(sprintf(
                'Missing event handler method %s for aggregate root %s',
                $handler,
                get_class($this),
            ));
        }

        $this->{$handler}($event);
    }

    protected function recordThat(AbstractDomainEvent $event, bool $apply = true): void
    {
        ++$this->version;
        $this->recordedEvents[] = $event->withVersion($this->version);

        if ($apply) {
            $this->apply($event);
        }
    }

    protected function determineEventHandlerMethodFor(AbstractDomainEvent $event): string
    {
        return 'when' . implode(array_slice(explode('\\', \get_class($event)), -1));
    }
}
