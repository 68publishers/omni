<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Bridge\Symfony\Messenger\Stamp;

use Symfony\Component\Messenger\Stamp\NonSendableStampInterface;

final class TransportStamp implements NonSendableStampInterface
{
    public function __construct(
        private readonly string $transportName,
    ) {}

    public function getTransportName(): string
    {
        return $this->transportName;
    }
}
