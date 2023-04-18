<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Bridge\Nette\DI;

use Nette\DI\CompilerExtension;
use SixtyEightPublishers\DoctrineBridge\Bridge\Nette\DI\DatabaseTypeProviderInterface;

final class AutoRegisterDoctrineTypesExtension extends CompilerExtension implements DatabaseTypeProviderInterface
{
    use AutoRegisterDoctrineTypesTrait;

    /**
     * @param array<string> $directories
     */
    public function __construct(
        private readonly array $directories,
    ) {}

    /**
     * @return array<string>|null
     */
    protected function getDatabaseTypesDirectories(): ?array
    {
        return $this->directories;
    }
}
