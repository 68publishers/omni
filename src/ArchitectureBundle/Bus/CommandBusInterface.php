<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Bus;

use SixtyEightPublishers\ArchitectureBundle\Command\CommandInterface;

interface CommandBusInterface
{
	/**
	 * @param \SixtyEightPublishers\ArchitectureBundle\Command\CommandInterface $message
	 * @param \Symfony\Component\Messenger\Stamp\StampInterface[]               $stamps
	 *
	 * @return void
	 */
	public function dispatch(CommandInterface $message, array $stamps = []): void;
}
