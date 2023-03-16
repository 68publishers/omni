<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Infrastructure\InMemory;

use function array_filter;
use function array_values;

final class MemorySection implements MemorySectionInterface
{
    /** @var array<object> */
    private array $storage = [];

    public function __construct(
        private readonly string $name,
    ) {}

    public function getName(): string
    {
        return $this->name;
    }

    public function has(string $id): bool
    {
        return isset($this->storage[$id]);
    }

    public function get(string $id): ?object
    {
        return $this->storage[$id] ?? null;
    }

    public function add(string $id, object $object): void
    {
        $this->storage[$id] = $object;
    }

    public function remove(string $id): void
    {
        if ($this->has($id)) {
            unset($this->storage[$id]);
        }
    }

    public function filter(callable $callback): array
    {
        return array_values(array_filter($this->storage, $callback));
    }

    public function filterOne(callable $callback): ?object
    {
        foreach ($this->storage as $item) {
            if ($callback($item)) {
                return $item;
            }
        }

        return null;
    }

    public function all(): array
    {
        return array_values($this->storage);
    }
}
