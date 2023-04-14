<?php

declare(strict_types=1);

namespace SixtyEightPublishers\MailingBundle\ReadModel\View;

use SixtyEightPublishers\MailingBundle\Domain\ValueObject\Locale;
use SixtyEightPublishers\MailingBundle\Domain\ValueObject\MessageBody;
use SixtyEightPublishers\MailingBundle\Domain\ValueObject\Subject;

final class MailSource
{
    public function __construct(
        public readonly SourceType $type,
        public readonly ?Subject $subject,
        public readonly MessageBody $messageBody,
        public readonly ?MessageBody $layoutBody,
        public readonly Locale $locale,
    ) {}
}
