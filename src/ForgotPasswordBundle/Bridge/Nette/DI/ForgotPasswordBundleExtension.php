<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ForgotPasswordBundle\Bridge\Nette\DI;

use Nette\Schema\Expect;
use Nette\Schema\Schema;
use Nette\DI\CompilerExtension;
use Nette\InvalidArgumentException;
use SixtyEightPublishers\UserBundle\Bridge\Nette\DI\UserBundleExtension;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\Aggregate\PasswordRequest;
use SixtyEightPublishers\ArchitectureBundle\Bridge\Nette\DI\CompilerExtensionUtilsTrait;

final class ForgotPasswordBundleExtension extends CompilerExtension
{
	use CompilerExtensionUtilsTrait;

	/**
	 * {@inheritDoc}
	 */
	public function getConfigSchema(): Schema
	{
		return Expect::structure([
			'entity_classname' => Expect::structure([
				'password_request' => Expect::string(PasswordRequest::class)
					->assert(static function (string $classname) {
						if (!is_a($classname, PasswordRequest::class, TRUE)) {
							throw new InvalidArgumentException(sprintf(
								'Classname must be %s or it\'s inheritor.',
								PasswordRequest::class
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
		$this->requireCompilerExtension(UserBundleExtension::class);
		$this->requireCompilerExtension(InfrastructureExtensionInterface::class);

		$this->setBundleParameter('entity_classname', [
			'password_request' => $this->config->entity_classname->password_request,
		]);

		$this->loadConfigurationDir(__DIR__ . '/config/password_request');
	}
}
