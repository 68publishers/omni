<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Application\HttpLink;

interface LinkInterface
{
    public function getName(): string;

    /**
     * @return array<string, mixed>
     */
    public function getParameters(): array;
}
