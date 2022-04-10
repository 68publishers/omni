<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Infrastructure\InMemory;

interface MemoryStorageInterface
{
	/**
	 * @param string $name
	 *
	 * @return \SixtyEightPublishers\ArchitectureBundle\Infrastructure\InMemory\MemorySectionInterface
	 */
	public function section(string $name): MemorySectionInterface;

	/**
	 * @return \SixtyEightPublishers\ArchitectureBundle\Infrastructure\InMemory\MemorySectionInterface[]
	 */
	public function all(): array;
}
