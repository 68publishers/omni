<?php

declare(strict_types=1);

namespace SixtyEightPublishers\MailingBundle\Domain;

use SixtyEightPublishers\MailingBundle\Domain\ValueObject\Code;
use SixtyEightPublishers\MailingBundle\Domain\ValueObject\MailId;

interface CodeGuardInterface
{
    public function __invoke(MailId $mailId, Code $code): void;
}
