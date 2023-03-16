<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ForgotPasswordBundle\Bridge\Nette\DI;

use Nette\DI\CompilerExtension;
use Nette\InvalidArgumentException;
use Nette\Schema\Expect;
use Nette\Schema\Schema;
use SixtyEightPublishers\ArchitectureBundle\Bridge\Nette\DI\CompilerExtensionUtilsTrait;
use SixtyEightPublishers\ArchitectureBundle\Bridge\Nette\DI\ExtendedAggregatesResolverTrait;
use SixtyEightPublishers\ForgotPasswordBundle\Bridge\Nette\DI\Config\ForgotPasswordBundleConfig;
use SixtyEightPublishers\ForgotPasswordBundle\Domain\PasswordRequest;
use SixtyEightPublishers\UserBundle\Bridge\Nette\DI\UserBundleExtension;
use function assert;
use function is_a;
use function sprintf;

final class ForgotPasswordBundleExtension extends CompilerExtension
{
    use CompilerExtensionUtilsTrait;
    use ExtendedAggregatesResolverTrait;

    public function getConfigSchema(): Schema
    {
        return Expect::structure([
            'aggregate_classname' => Expect::structure([
                'password_request' => Expect::string(PasswordRequest::class)
                    ->assert(static function (string $classname) {
                        if (!is_a($classname, PasswordRequest::class, true)) {
                            throw new InvalidArgumentException(sprintf(
                                'Classname must be %s or it\'s inheritor.',
                                PasswordRequest::class,
                            ));
                        }

                        return true;
                    }),
            ]),
        ]);
    }

    public function loadConfiguration(): void
    {
        $this->requireCompilerExtension(UserBundleExtension::class);
        $this->requireCompilerExtension(InfrastructureExtensionInterface::class);

        $config = $this->getConfig();
        assert($config instanceof ForgotPasswordBundleConfig);

        $this->setBundleParameter('aggregate_classname', [
            'password_request' => $config->aggregate_classname->password_request,
        ]);

        $this->loadConfigurationDir(__DIR__ . '/definitions/password_request');
    }

    /**
     * @return array<class-string, class-string>
     */
    public function resolveExtendedAggregates(): array
    {
        $config = $this->getConfig();
        assert($config instanceof ForgotPasswordBundleConfig);

        return [
            PasswordRequest::class => $config->aggregate_classname->password_request,
        ];
    }
}
