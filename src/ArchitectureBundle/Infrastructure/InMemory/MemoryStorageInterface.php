<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Infrastructure\InMemory;

interface MemoryStorageInterface
{
    public function section(string $name): MemorySectionInterface;

    /**
     * @return array<MemorySectionInterface>
     */
    public function all(): array;
}
