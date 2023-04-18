<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Bridge\Nette\DI;

use Nette\DI\CompilerExtension;
use SixtyEightPublishers\DoctrineBridge\Bridge\Nette\DI\EntityMappingProviderInterface;

final class AutoRegisterDoctrineXmlMappingExtension extends CompilerExtension implements EntityMappingProviderInterface
{
    use AutoRegisterDoctrineXmlMappingTrait;

    /**
     * @param array<string> $directories
     */
    public function __construct(
        private readonly array $directories,
    ) {}

    /**
     * @return array<string>|null
     */
    protected function getMappingDirectories(): ?array
    {
        return $this->directories;
    }
}
