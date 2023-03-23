<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Application\HttpLink;

final class Link implements LinkInterface
{
    /**
     * @param array<string, mixed> $parameters
     */
    public function __construct(
        private readonly string $name,
        private readonly array $parameters = [],
    ) {}

    public function getName(): string
    {
        return $this->name;
    }

    public function getParameters(): array
    {
        return $this->parameters;
    }
}
