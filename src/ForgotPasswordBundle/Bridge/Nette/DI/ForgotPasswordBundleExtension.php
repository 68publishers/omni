<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ForgotPasswordBundle\Bridge\Nette\DI;

use Nette\DI\CompilerExtension;
use Nette\InvalidArgumentException;
use Nette\Schema\Expect;
use Nette\Schema\Schema;
use SixtyEightPublishers\ArchitectureBundle\Bridge\Nette\DI\ArchitectureBundleExtension;
use SixtyEightPublishers\ArchitectureBundle\Bridge\Nette\DI\CompilerExtensionUtilsTrait;
use SixtyEightPublishers\ForgotPasswordBundle\Bridge\Nette\DI\Config\AggregateConfig;
use SixtyEightPublishers\ForgotPasswordBundle\Bridge\Nette\DI\Config\AggregateTypeConfig;
use SixtyEightPublishers\ForgotPasswordBundle\Bridge\Nette\DI\Config\ForgotPasswordBundleConfig;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\PasswordRequest;
use SixtyEightPublishers\UserBundle\Bridge\Nette\DI\UserBundleExtension;
use function array_unique;
use function assert;
use function is_a;
use function sprintf;

final class ForgotPasswordBundleExtension extends CompilerExtension
{
    use CompilerExtensionUtilsTrait;

    public function getConfigSchema(): Schema
    {
        return Expect::structure([
            'aggregate' => Expect::structure([
                'password_request' => Expect::structure([
                    'classname' => Expect::string(PasswordRequest::class)
                        ->assert(static function (string $classname) {
                            if (!is_a($classname, PasswordRequest::class, true)) {
                                throw new InvalidArgumentException(sprintf(
                                    'Classname must be %s or it\'s inheritor.',
                                    PasswordRequest::class,
                                ));
                            }

                            return true;
                        }),
                    'event_store_name' => Expect::string(),
                ])->castTo(AggregateTypeConfig::class),
            ])->castTo(AggregateConfig::class),
        ])->castTo(ForgotPasswordBundleConfig::class);
    }

    public function loadConfiguration(): void
    {
        $this->requireCompilerExtension(UserBundleExtension::class);
        $this->requireCompilerExtension(InfrastructureExtensionInterface::class);

        $config = $this->getConfig();
        assert($config instanceof ForgotPasswordBundleConfig);

        $this->setBundleParameter('aggregate_classname', [
            'password_request' => $config->aggregate->password_request->classname,
        ]);

        $this->loadConfigurationDir(__DIR__ . '/definitions/password_request');
    }

    public function beforeCompile(): void
    {
        $config = $this->getConfig();
        assert($config instanceof ForgotPasswordBundleConfig);

        if (null === $config->aggregate->password_request->event_store_name) {
            return;
        }

        $architectureBundle = $this->requireCompilerExtension(ArchitectureBundleExtension::class);
        assert($architectureBundle instanceof ArchitectureBundleExtension);

        foreach (array_unique([PasswordRequest::class, $config->aggregate->password_request->classname]) as $aggregateClassname) {
            $architectureBundle->resolveEventStoreForAggregateClassname($aggregateClassname, $config->aggregate->password_request->event_store_name);
        }
    }
}
