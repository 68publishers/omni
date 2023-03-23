<?php

declare(strict_types=1);

namespace SixtyEightPublishers\MailingBundle\Bridge\Nette\Template;

use Nette\Bridges\ApplicationLatte\Template as NetteTemplate;
use SixtyEightPublishers\MailingBundle\Application\Template\TemplateInterface;
use SixtyEightPublishers\TranslationBridge\Localization\TranslatorLocalizerInterface;
use function array_merge;

final class Template implements TemplateInterface
{
    /**
     * @param array<string, mixed> $arguments
     */
    public function __construct(
        private readonly NetteTemplate $template,
        private readonly TranslatorLocalizerInterface $translatorLocalizer,
        private readonly string $file,
        private readonly string $locale,
        private readonly array $arguments = [],
    ) {}

    public function unwrap(): NetteTemplate
    {
        return $this->template;
    }

    public function withArguments(array $arguments): TemplateInterface
    {
        $arguments = array_merge($this->arguments, $arguments);

        return new self($this->template, $this->translatorLocalizer, $this->file, $this->locale, $arguments);
    }

    public function render(array $arguments): string
    {
        $arguments = array_merge($this->arguments, $arguments);
        $locale = $this->translatorLocalizer->getLocale();

        if ($locale !== $this->locale) {
            $this->translatorLocalizer->setLocale($this->locale);
        }

        try {
            $rendered = $this->template->renderToString($this->file, $arguments);
        } finally {
            if ($locale !== $this->locale) {
                $this->translatorLocalizer->setLocale($locale);
            }
        }

        return $rendered;
    }
}
