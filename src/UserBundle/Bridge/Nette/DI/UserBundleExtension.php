<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\Bridge\Nette\DI;

use Nette\Schema\Expect;
use Nette\Schema\Schema;
use Nette\DI\CompilerExtension;
use Nette\InvalidArgumentException;
use Nette\Bridges\HttpDI\HttpExtension;
use Nette\Bridges\SecurityDI\SecurityExtension;
use SixtyEightPublishers\UserBundle\Domain\Aggregate\User;
use SixtyEightPublishers\ArchitectureBundle\Bridge\Nette\DI\ArchitectureBundleExtension;
use SixtyEightPublishers\ArchitectureBundle\Bridge\Nette\DI\CompilerExtensionUtilsTrait;

final class UserBundleExtension extends CompilerExtension
{
	use CompilerExtensionUtilsTrait;

	/**
	 * {@inheritDoc}
	 */
	public function getConfigSchema(): Schema
	{
		return Expect::structure([
			'entity_classname' => Expect::structure([
				'user' => Expect::string(User::class)
					->assert(static function (string $classname) {
						if (!is_a($classname, User::class, TRUE)) {
							throw new InvalidArgumentException(sprintf(
								'Classname must be %s or it\'s inheritor.',
								User::class
							));
						}

						return TRUE;
					}),
			]),
		]);
	}

	/**
	 * {@inheritDoc}
	 */
	public function loadConfiguration(): void
	{
		$this->requireCompilerExtension(ArchitectureBundleExtension::class);
		$this->requireCompilerExtension(InfrastructureExtensionInterface::class);
		$this->requireCompilerExtension(SecurityExtension::class);
		$this->requireCompilerExtension(HttpExtension::class);

		$this->setBundleParameter('entity_classname', [
			'user' => $this->config->entity_classname->user,
		]);

		$this->loadConfigurationDir(__DIR__ . '/config/user_bundle');
	}
}
