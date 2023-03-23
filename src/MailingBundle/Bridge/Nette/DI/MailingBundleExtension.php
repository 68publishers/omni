<?php

declare(strict_types=1);

namespace SixtyEightPublishers\MailingBundle\Bridge\Nette\DI;

use Nette\Bridges\MailDI\MailExtension;
use Nette\DI\CompilerExtension;
use Nette\DI\Definitions\ServiceDefinition;
use Nette\DI\Definitions\Statement;
use Nette\InvalidArgumentException;
use Nette\Schema\Expect;
use Nette\Schema\Schema;
use SixtyEightPublishers\ArchitectureBundle\Bridge\Nette\DI\ArchitectureBundleExtension;
use SixtyEightPublishers\ArchitectureBundle\Bridge\Nette\DI\CompilerExtensionUtilsTrait;
use SixtyEightPublishers\MailingBundle\Application\Address;
use SixtyEightPublishers\MailingBundle\Bridge\Nette\DI\Config\AggregateConfig;
use SixtyEightPublishers\MailingBundle\Bridge\Nette\DI\Config\AggregateTypeConfig;
use SixtyEightPublishers\MailingBundle\Bridge\Nette\DI\Config\MailingBundleConfig;
use SixtyEightPublishers\MailingBundle\Bridge\Nette\DI\Config\SenderConfig;
use SixtyEightPublishers\MailingBundle\Domain\Mail;
use function assert;
use function sprintf;

final class MailingBundleExtension extends CompilerExtension
{
    use CompilerExtensionUtilsTrait;

    public function getConfigSchema(): Schema
    {
        return Expect::structure([
            'aggregate' => Expect::structure([
                'mail' => Expect::structure([
                    'classname' => Expect::string(Mail::class)
                        ->assert(static function (string $classname) {
                            if (!is_a($classname, Mail::class, true)) {
                                throw new InvalidArgumentException(sprintf(
                                    'Classname must be %s or it\'s inheritor.',
                                    Mail::class,
                                ));
                            }

                            return true;
                        }),
                    'event_store_name' => Expect::string(),
                ])->castTo(AggregateTypeConfig::class),
            ])->castTo(AggregateConfig::class),

            'default_sender' => Expect::structure([
                'email_address' => Expect::string()->dynamic()->nullable(),
                'name' => Expect::string()->dynamic()->nullable(),
            ])->castTo(SenderConfig::class),

            'default_template_arguments' => Expect::arrayOf(Expect::mixed(), Expect::string()),
        ])->castTo(MailingBundleConfig::class);
    }

    public function loadConfiguration(): void
    {
        $this->requireCompilerExtension(ArchitectureBundleExtension::class);
        $this->requireCompilerExtension(InfrastructureExtensionInterface::class);
        $this->requireCompilerExtension(MailExtension::class);

        $config = $this->getConfig();
        assert($config instanceof MailingBundleConfig);

        $this->setBundleParameter('aggregate_classname', [
            'mail' => $config->aggregate->mail->classname,
        ]);

        $this->loadConfigurationDir(__DIR__ . '/definitions/mailing_bundle');
    }

    public function beforeCompile(): void
    {
        $builder = $this->getContainerBuilder();
        $config = $this->getConfig();
        assert($config instanceof MailingBundleConfig);

        # default sender
        if (null !== $config->default_sender->email_address) {
            $sendMailCommandDefinition = $builder->getDefinition($this->prefix('application.command_handler.send_mail'));
            assert($sendMailCommandDefinition instanceof ServiceDefinition);

            $sendMailCommandDefinition->setArgument('defaultFrom', new Statement(Address::class, [
                'emailAddress' => $config->default_sender->email_address,
                'name' => $config->default_sender->name,
            ]));
        }

        $provideArgumentsTemplateExtender = $builder->getDefinition($this->prefix('application.template_extender.provide_variables'));
        assert($provideArgumentsTemplateExtender instanceof ServiceDefinition);

        $provideArgumentsTemplateExtender->getCreator()->arguments['arguments'] = $config->default_template_arguments;

        # Mail aggregate event store
        if (null === $config->aggregate->mail->event_store_name) {
            return;
        }

        $architectureBundle = $this->requireCompilerExtension(ArchitectureBundleExtension::class);
        assert($architectureBundle instanceof ArchitectureBundleExtension);

        foreach (array_unique([Mail::class, $config->aggregate->mail->classname]) as $aggregateClassname) {
            $architectureBundle->resolveEventStoreForAggregateClassname($aggregateClassname, $config->aggregate->mail->event_store_name);
        }
    }
}
