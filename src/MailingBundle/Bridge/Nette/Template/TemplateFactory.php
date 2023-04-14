<?php

declare(strict_types=1);

namespace SixtyEightPublishers\MailingBundle\Bridge\Nette\Template;

use Latte\Loaders\StringLoader;
use Nette\Application\LinkGenerator;
use Nette\Application\UI\TemplateFactory as NetteTemplateFactory;
use Nette\Bridges\ApplicationLatte\Template as NetteTemplate;
use Nette\Localization\Translator;
use SixtyEightPublishers\MailingBundle\Application\Template\TemplateFactoryInterface;
use SixtyEightPublishers\MailingBundle\Application\Template\TemplateInterface;
use SixtyEightPublishers\MailingBundle\ReadModel\View\SourceType;
use SixtyEightPublishers\TranslationBridge\Localization\TranslatorLocalizerInterface;
use function assert;

final class TemplateFactory implements TemplateFactoryInterface
{
    public function __construct(
        private readonly NetteTemplateFactory $templateFactory,
        private readonly LinkGenerator $linkGenerator,
        private readonly Translator $translator,
        private readonly TranslatorLocalizerInterface $translatorLocalizer,
    ) {}

    public function create(SourceType $sourceType, string $content, ?string $layout, string $locale): TemplateInterface
    {
        $template = $this->templateFactory->createTemplate();
        assert($template instanceof NetteTemplate);

        $file = $this->prepareSource($sourceType, $content, $layout, $template);

        $template->setTranslator($this->translator);
        $template->getLatte()->addProvider('uiControl', $this->linkGenerator);

        return new Template($template, $this->translatorLocalizer, $file, $locale);
    }

    private function prepareSource(SourceType $sourceType, string $content, ?string $layout, NetteTemplate $template): string
    {
        $latte = $template->getLatte();

        if (SourceType::FILE_PATH === $sourceType) {
            $loader = $latte->getLoader();

            if (null !== $layout) {
                $loader = new AliasedLatteLoader($loader, [
                    '@layout' => $layout,
                ]);
            }

            $template->getLatte()->setLoader($loader);

            return $content;
        }

        $templates = [
            'content' => $content,
        ];

        if (null !== $layout) {
            $templates['@layout'] = $layout;
        }

        $template->getLatte()->setLoader(new StringLoader($templates));

        return 'content';
    }
}
