<?php

declare(strict_types=1);

namespace SixtyEightPublishers\MailingBundle\Bridge\Nette\DI\Config;

final class DirectoryConfig
{
    public string $path;

    public string $extension;

    public int $priority;
}