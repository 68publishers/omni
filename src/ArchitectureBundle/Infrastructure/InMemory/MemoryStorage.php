<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Infrastructure\InMemory;

final class MemoryStorage implements MemoryStorageInterface
{
	/** @var \SixtyEightPublishers\ArchitectureBundle\Infrastructure\InMemory\MemorySection[]  */
	private array $sections = [];

	/**
	 * {@inheritDoc}
	 */
	public function section(string $name): MemorySectionInterface
	{
		return $this->sections[$name] ?? $this->sections[$name] = new MemorySection($name);
	}

	/**
	 * {@inheritDoc}
	 */
	public function all(): array
	{
		return array_values($this->sections);
	}
}
