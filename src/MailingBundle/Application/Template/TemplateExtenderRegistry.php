<?php

declare(strict_types=1);

namespace SixtyEightPublishers\MailingBundle\Application\Template;

final class TemplateExtenderRegistry implements TemplateExtenderInterface
{
    /**
     * @param array<TemplateExtenderInterface> $templateExtenders
     */
    public function __construct(
        private readonly array $templateExtenders,
    ) {}

    public function extend(TemplateInterface $template): TemplateInterface
    {
        foreach ($this->templateExtenders as $templateExtender) {
            $template = $templateExtender->extend($template);
        }

        return $template;
    }
}
