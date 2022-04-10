<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Event;

use SixtyEightPublishers\ArchitectureBundle\Message\Message;

abstract class AbstractEvent extends Message implements EventInterface
{
}
