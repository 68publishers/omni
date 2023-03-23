<?php

declare(strict_types=1);

namespace SixtyEightPublishers\MailingBundle\Application\Template;

use SixtyEightPublishers\MailingBundle\ReadModel\View\SourceType;

interface TemplateFactoryInterface
{
    public function create(SourceType $sourceType, string $content, string $locale): TemplateInterface;
}
