<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Domain\Guard;

use SixtyEightPublishers\ArchitectureBundle\Command\CommandInterface;
use SixtyEightPublishers\ArchitectureBundle\Domain\Exception\CommandConsistencyException;

final class CommandConsistencyGuardRegistry implements CommandConsistencyGuardInterface
{
	private array $commandConsistencyGuards;

	/**
	 * @param \SixtyEightPublishers\ArchitectureBundle\Domain\Guard\CommandConsistencyGuardInterface[] $commandConsistencyGuards
	 */
	public function __construct(array $commandConsistencyGuards)
	{
		$this->commandConsistencyGuards = (static fn (CommandConsistencyGuardInterface ...$commandConsistencyGuards): array => $commandConsistencyGuards)(...$commandConsistencyGuards);
	}

	/**
	 * {@inheritDoc}
	 */
	public function __invoke(CommandInterface $command): void
	{
		$exception = CommandConsistencyException::empty();

		foreach ($this->commandConsistencyGuards as $commandConsistencyGuard) {
			try {
				$commandConsistencyGuard($command);
			} catch (CommandConsistencyException $e) {
				$exception = $exception->withException($e);
			}
		}

		if (0 < count($exception->getMessages())) {
			throw $exception;
		}
	}
}
