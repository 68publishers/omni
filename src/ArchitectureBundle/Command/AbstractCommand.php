<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Command;

use SixtyEightPublishers\ArchitectureBundle\Message\Message;

abstract class AbstractCommand extends Message implements CommandInterface
{
}
