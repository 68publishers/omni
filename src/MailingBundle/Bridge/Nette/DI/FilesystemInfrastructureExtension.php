<?php

declare(strict_types=1);

namespace SixtyEightPublishers\MailingBundle\Bridge\Nette\DI;

use Nette\DI\CompilerExtension;
use Nette\DI\Definitions\ServiceDefinition;
use Nette\Schema\Expect;
use Nette\Schema\Schema;
use SixtyEightPublishers\ArchitectureBundle\Bridge\Nette\DI\CompilerExtensionUtilsTrait;
use SixtyEightPublishers\MailingBundle\Bridge\Nette\DI\Config\DirectoryConfig;
use SixtyEightPublishers\MailingBundle\Bridge\Nette\DI\Config\FilesystemInfrastructureConfig;
use function assert;

final class FilesystemInfrastructureExtension extends CompilerExtension implements InfrastructureExtensionInterface
{
    use CompilerExtensionUtilsTrait;

    public function getConfigSchema(): Schema
    {
        return Expect::structure([
            'directories' => Expect::listOf(
                Expect::structure([
                    'path' => Expect::string()->required(),
                    'extension' => Expect::string('latte'),
                    'priority' => Expect::int(0),
                ])->castTo(DirectoryConfig::class),
            ),
        ])->castTo(FilesystemInfrastructureConfig::class);
    }

    public function loadConfiguration(): void
    {
        $this->requireCompilerExtension(MailingBundleExtension::class);
        $this->checkCompilerExtensionConcurrency(InfrastructureExtensionInterface::class);
        $this->loadConfigurationDir(__DIR__ . '/definitions/filesystem_infrastructure');

        $config = $this->getConfig();
        assert($config instanceof FilesystemInfrastructureConfig);

        foreach ($config->directories as $directory) {
            $this->registerDirectory($directory->path, $directory->extension, $directory->priority);
        }
    }

    public function registerDirectory(string $directory, string $extension, int $priority = 0): void
    {
        $builder = $this->getContainerBuilder();
        $mailSourceLocatorDefinition = $builder->getDefinition($this->prefix('infrastructure.locator.default'));
        assert($mailSourceLocatorDefinition instanceof ServiceDefinition);

        $mailSourceLocatorDefinition->addSetup('registerDirectory', [
            'directory' => $directory,
            'extension' => $extension,
            'priority' => $priority,
        ]);
    }
}
