<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Bridge\Symfony\Messenger\Transport;

use Nette\DI\Container;
use Symfony\Component\Messenger\Envelope;
use Fmasa\Messenger\Transport\SendersLocator;
use Fmasa\Messenger\Exceptions\SenderNotFound;
use Symfony\Component\Messenger\Transport\Sender\SenderInterface;
use Symfony\Component\Messenger\Transport\Sender\SendersLocatorInterface;
use SixtyEightPublishers\ArchitectureBundle\Bridge\Symfony\Messenger\Stamp\TransportStamp;

final class StampBasedSendersLocator implements SendersLocatorInterface
{
	private SendersLocatorInterface $inner;

	private Container $container;

	/**
	 * @param \Symfony\Component\Messenger\Transport\Sender\SendersLocatorInterface $inner
	 * @param \Nette\DI\Container                                                   $container
	 */
	public function __construct(SendersLocatorInterface $inner, Container $container)
	{
		$this->inner = $inner;
		$this->container = $container;
	}

	/**
	 * {@inheritDoc}
	 */
	public function getSenders(Envelope $envelope): iterable
	{
		$transportStamps = $envelope->all(TransportStamp::class);

		if (empty($transportStamps)) {
			foreach ($this->inner->getSenders($envelope) as $sender) {
				yield $sender;
			}

			return;
		}

		/** @var \SixtyEightPublishers\ArchitectureBundle\Bridge\Symfony\Messenger\Stamp\TransportStamp $transportStamp */
		foreach ($transportStamps as $transportStamp) {
			yield $this->getSenderByAlias($transportStamp->getTransportName());
		}
	}

	/**
	 * @param string $alias
	 *
	 * @return \Symfony\Component\Messenger\Transport\Sender\SenderInterface
	 */
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
