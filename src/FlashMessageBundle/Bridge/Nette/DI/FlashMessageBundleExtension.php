<?php

declare(strict_types=1);

namespace SixtyEightPublishers\FlashMessageBundle\Bridge\Nette\DI;

use Nette\DI\CompilerExtension;
use Nette\Bridges\HttpDI\HttpExtension;
use Nette\Bridges\ApplicationDI\ApplicationExtension;
use SixtyEightPublishers\ArchitectureBundle\Bridge\Nette\DI\CompilerExtensionUtilsTrait;

final class FlashMessageBundleExtension extends CompilerExtension
{
	use CompilerExtensionUtilsTrait;

	/**
	 * {@inheritDoc}
	 */
	public function loadConfiguration(): void
	{
		$this->requireCompilerExtension(ApplicationExtension::class);
		$this->requireCompilerExtension(HttpExtension::class);
		$this->loadConfigurationDir(__DIR__ . '/config/flash_message');
	}
}
