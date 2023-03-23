<?php

declare(strict_types=1);

namespace SixtyEightPublishers\MailingBundle\Application\Template;

interface TemplateExtenderInterface
{
    public function extend(TemplateInterface $template): TemplateInterface;
}
