<?php

declare(strict_types=1);

namespace SixtyEightPublishers\MailingBundle\Bridge\Nette\DI\Config;

final class MailingBundleConfig
{
    public AggregateConfig $aggregate;

    public SenderConfig $default_sender;
}
