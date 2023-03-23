<?php

declare(strict_types=1);

namespace SixtyEightPublishers\MailingBundle\Bridge\Nette\DI\Config;

use Nette\DI\Definitions\Statement;

final class SenderConfig
{
    public string|Statement|null $email_address = null;

    public string|Statement|null $name = null;
}
