<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ProjectionBundle\Projection;

use Symfony\Component\Messenger\Handler\MessageSubscriberInterface;

interface ProjectionInterface extends MessageSubscriberInterface
{
    public static function getProjectionName(): string;

    /**
     * @return iterable<EventDefinition>
     */
    public static function defineEvents(): iterable;
}
