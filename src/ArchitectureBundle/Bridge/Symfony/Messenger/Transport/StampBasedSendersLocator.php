<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Bridge\Symfony\Messenger\Transport;

use Fmasa\Messenger\Exceptions\SenderNotFound;
use Fmasa\Messenger\Transport\SendersLocator;
use Nette\DI\Container;
use SixtyEightPublishers\ArchitectureBundle\Bridge\Symfony\Messenger\Stamp\TransportStamp;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Transport\Sender\SenderInterface;
use Symfony\Component\Messenger\Transport\Sender\SendersLocatorInterface;
use function assert;

final class StampBasedSendersLocator implements SendersLocatorInterface
{
    public function __construct(
        private readonly SendersLocatorInterface $inner,
        private readonly Container $container,
    ) {}

    public function getSenders(Envelope $envelope): iterable
    {
        $transportStamps = $envelope->all(TransportStamp::class);

        if (empty($transportStamps)) {
            foreach ($this->inner->getSenders($envelope) as $alias => $sender) {
                yield $alias => $sender;
            }

            return;
        }

        foreach ($transportStamps as $transportStamp) {
            assert($transportStamp instanceof TransportStamp);
            $alias = $transportStamp->getTransportName();

            yield $alias => $this->getSenderByAlias($alias);
        }
    }

    private function getSenderByAlias(string $alias): SenderInterface
    {
        foreach ($this->container->findByTag(SendersLocator::TAG_SENDER_ALIAS) as $serviceName => $serviceAlias) {
            if ($serviceAlias !== $alias) {
                continue;
            }

            $sender = $this->container->getService($serviceName);
            assert($sender instanceof SenderInterface);

            return $sender;
        }

        throw SenderNotFound::withAlias($alias);
    }
}
