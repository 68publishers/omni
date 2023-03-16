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
use SixtyEightPublishers\ArchitectureBundle\Bridge\Nette\DI\ExtendedAggregatesResolverTrait;
use SixtyEightPublishers\UserBundle\Bridge\Nette\DI\Config\AggregateClassnameConfig;
use SixtyEightPublishers\UserBundle\Bridge\Nette\DI\Config\UserBundleConfig;
use SixtyEightPublishers\UserBundle\Domain\User;
use function assert;
use function is_a;
use function sprintf;

final class UserBundleExtension extends CompilerExtension
{
    use CompilerExtensionUtilsTrait;
    use ExtendedAggregatesResolverTrait;

    public function getConfigSchema(): Schema
    {
        return Expect::structure([
            'aggregate_classname' => Expect::structure([
                'user' => Expect::string(User::class)
                    ->assert(static function (string $classname) {
                        if (!is_a($classname, User::class, true)) {
                            throw new InvalidArgumentException(sprintf(
                                'Classname must be %s or it\'s inheritor.',
                                User::class,
                            ));
                        }

                        return true;
                    }),
            ])->castTo(AggregateClassnameConfig::class),
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
            'user' => $config->aggregate_classname->user,
        ]);

        $this->loadConfigurationDir(__DIR__ . '/definitions/user_bundle');
    }

    /**
     * @return array<class-string, class-string>
     */
    public function resolveExtendedAggregates(): array
    {
        $config = $this->getConfig();
        assert($config instanceof UserBundleConfig);

        return [
            User::class => $config->aggregate_classname->user,
        ];
    }
}
