<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Bridge\Symfony\Messenger\Stamp;

use Symfony\Component\Messenger\Stamp\StampInterface;

final class NoTransactionStamp implements StampInterface
{
}
