<?php

declare(strict_types=1);

namespace SixtyEightPublishers\MailingBundle\Application\Template;

final class ProvideArgumentsTemplateExtender implements TemplateExtenderInterface
{
    /**
     * @param array<string, mixed> $arguments
     */
    public function __construct(
        private readonly array $arguments,
    ) {}

    public function extend(TemplateInterface $template): TemplateInterface
    {
        if (!empty($this->arguments)) {
            $template = $template->withArguments($this->arguments);
        }

        return $template;
    }
}
