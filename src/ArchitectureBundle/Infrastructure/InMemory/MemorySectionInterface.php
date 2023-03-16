<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Infrastructure\InMemory;

interface MemorySectionInterface
{
    public function getName(): string;

    public function has(string $id): bool;

    public function get(string $id): ?object;

    public function add(string $id, object $object): void;

    public function remove(string $id): void;

    /**
     * @return array<object>
     */
    public function filter(callable $callback): array;

    public function filterOne(callable $callback): ?object;

    /**
     * @return array<object>
     */
    public function all(): array;
}
