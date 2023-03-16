<?php

declare(strict_types=1);

namespace SixtyEightPublishers\FlashMessageBundle\Bridge\Nette\DI;

use Nette\Bridges\ApplicationDI\ApplicationExtension;
use Nette\Bridges\HttpDI\HttpExtension;
use Nette\DI\CompilerExtension;
use SixtyEightPublishers\ArchitectureBundle\Bridge\Nette\DI\CompilerExtensionUtilsTrait;

final class FlashMessageBundleExtension extends CompilerExtension
{
    use CompilerExtensionUtilsTrait;

    public function loadConfiguration(): void
    {
        $this->requireCompilerExtension(ApplicationExtension::class);
        $this->requireCompilerExtension(HttpExtension::class);
        $this->loadConfigurationDir(__DIR__ . '/definitions/flash_message');
    }
}
