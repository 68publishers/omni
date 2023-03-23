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
use function is_string;

final class FilesystemInfrastructureExtension extends CompilerExtension implements InfrastructureExtensionInterface
{
    use CompilerExtensionUtilsTrait;

    public function getConfigSchema(): Schema
    {
        $normalizeDirectories = static function (array $list): array {
            foreach ($list as $index => $item) {
                if (is_string($item)) {
                    $list[$index] = (object) [
                        'path' => $item,
                        'priority' => 0,
                    ];
                }
            }

            return $list;
        };

        return Expect::structure([
            'directories' => Expect::listOf(
                Expect::anyOf(
                    Expect::string(),
                    Expect::structure([
                        'path' => Expect::string()->required(),
                        'priority' => Expect::int(0),
                    ])->castTo(DirectoryConfig::class),
                ),
            )->before($normalizeDirectories),
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
            $this->registerDirectory($directory->path, $directory->priority);
        }
    }

    public function registerDirectory(string $directory, int $priority = 0): void
    {
        $builder = $this->getContainerBuilder();
        $mailSourceLocatorDefinition = $builder->getDefinition($this->prefix('infrastructure.locator.default'));
        assert($mailSourceLocatorDefinition instanceof ServiceDefinition);

        $mailSourceLocatorDefinition->addSetup('registerDirectory', [
            'directory' => $directory,
            'priority' => $priority,
        ]);
    }
}
