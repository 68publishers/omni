<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\Bridge\Nette\DI;

use Nette\Bridges\HttpDI\HttpExtension;
use Nette\Bridges\SecurityDI\SecurityExtension;
use Nette\DI\CompilerExtension;
use Nette\InvalidArgumentException;
use Nette\Schema\Expect;
use Nette\Schema\Schema;
use SixtyEightPublishers\ArchitectureBundle\Bridge\Nette\DI\ArchitectureBundleExtension;
use SixtyEightPublishers\ArchitectureBundle\Bridge\Nette\DI\CompilerExtensionUtilsTrait;
use SixtyEightPublishers\UserBundle\Bridge\Nette\DI\Config\AggregateConfig;
use SixtyEightPublishers\UserBundle\Bridge\Nette\DI\Config\AggregateTypeConfig;
use SixtyEightPublishers\UserBundle\Bridge\Nette\DI\Config\UserBundleConfig;
use SixtyEightPublishers\UserBundle\Domain\User;
use function array_unique;
use function assert;
use function is_a;
use function sprintf;

final class UserBundleExtension extends CompilerExtension
{
    use CompilerExtensionUtilsTrait;

    public function getConfigSchema(): Schema
    {
        return Expect::structure([
            'aggregate' => Expect::structure([
                'user' => Expect::structure([
                    'classname' => Expect::string(User::class)
                        ->assert(static function (string $classname) {
                            if (!is_a($classname, User::class, true)) {
                                throw new InvalidArgumentException(sprintf(
                                    'Classname must be %s or it\'s inheritor.',
                                    User::class,
                                ));
                            }

                            return true;
                        }),
                    'event_store_name' => Expect::string(),
                ])->castTo(AggregateTypeConfig::class),
            ])->castTo(AggregateConfig::class),
        ])->castTo(UserBundleConfig::class);
    }

    public function loadConfiguration(): void
    {
        $this->requireCompilerExtension(ArchitectureBundleExtension::class);
        $this->requireCompilerExtension(InfrastructureExtensionInterface::class);
        $this->requireCompilerExtension(SecurityExtension::class);
        $this->requireCompilerExtension(HttpExtension::class);

        $config = $this->getConfig();
        assert($config instanceof UserBundleConfig);

        $this->setBundleParameter('aggregate_classname', [
            'user' => $config->aggregate->user->classname,
        ]);

        $this->loadConfigurationDir(__DIR__ . '/definitions/user_bundle');
    }

    public function beforeCompile(): void
    {
        $config = $this->getConfig();
        assert($config instanceof UserBundleConfig);

        if (null === $config->aggregate->user->event_store_name) {
            return;
        }

        $architectureBundle = $this->requireCompilerExtension(ArchitectureBundleExtension::class);
        assert($architectureBundle instanceof ArchitectureBundleExtension);

        foreach (array_unique([User::class, $config->aggregate->user->classname]) as $aggregateClassname) {
            $architectureBundle->resolveEventStoreForAggregateClassname($aggregateClassname, $config->aggregate->user->event_store_name);
        }
    }
}
