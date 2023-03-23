<?php

declare(strict_types=1);

namespace SixtyEightPublishers\MailingBundle\Bridge\Nette\Template;

use Nette\Bridges\ApplicationLatte\Template as NetteTemplate;
use SixtyEightPublishers\MailingBundle\Application\Template\TemplateInterface;
use SixtyEightPublishers\TranslationBridge\Localization\TranslatorLocalizerInterface;

final class Template implements TemplateInterface
{
    public function __construct(
        private readonly NetteTemplate $template,
        private readonly TranslatorLocalizerInterface $translatorLocalizer,
        private readonly string $file,
        private readonly string $locale,
    ) {}

    public function unwrap(): NetteTemplate
    {
        return $this->template;
    }

    public function render(array $arguments): string
    {
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
