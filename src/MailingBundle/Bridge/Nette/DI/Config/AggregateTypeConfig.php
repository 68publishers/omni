<?php

declare(strict_types=1);

namespace SixtyEightPublishers\MailingBundle\Bridge\Nette\DI\Config;

final class AggregateTypeConfig
{
    /** @var class-string */
    public string $classname;

    public ?string $event_store_name = null;
}
