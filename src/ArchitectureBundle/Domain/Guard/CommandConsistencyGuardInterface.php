<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Domain\Guard;

use SixtyEightPublishers\ArchitectureBundle\Command\CommandInterface;

interface CommandConsistencyGuardInterface
{
	/**
	 * @param \SixtyEightPublishers\ArchitectureBundle\Command\CommandInterface $command
	 *
	 * @return void
	 * @throws \SixtyEightPublishers\ArchitectureBundle\Domain\Exception\CommandConsistencyException
	 */
	public function __invoke(CommandInterface $command): void;
}
