<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Infrastructure\InMemory;

use function array_values;

final class MemoryStorage implements MemoryStorageInterface
{
    /** @var array<MemorySection>  */
    private array $sections = [];

    public function section(string $name): MemorySectionInterface
    {
        return $this->sections[$name] ?? $this->sections[$name] = new MemorySection($name);
    }

    public function all(): array
    {
        return array_values($this->sections);
    }
}
