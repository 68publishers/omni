<?php

declare(strict_types=1);

namespace SixtyEightPublishers\FlashMessageBundle\Bridge\Nette\Ui;

use SixtyEightPublishers\FlashMessageBundle\Application\FlashMessageSubscriberInterface;
use SixtyEightPublishers\FlashMessageBundle\Domain\FlashMessage;
use SixtyEightPublishers\FlashMessageBundle\Domain\PhrasePrefix;
use function str_replace;

trait FlashMessageTrait
{
    private FlashMessageSubscriberInterface $flashMessageSubscriber;

    private ?PhrasePrefix $flashMessagePhrasePrefix = null;

    public function injectFlashMessageSubscriber(FlashMessageSubscriberInterface $flashMessageSubscriber): void
    {
        $this->flashMessageSubscriber = $flashMessageSubscriber;
    }

    /**
     * You can override this method with some custom strategy.
     */
    protected function getFlashMessagePhrasePrefix(): PhrasePrefix
    {
        if (null === $this->flashMessagePhrasePrefix) {
            $this->flashMessagePhrasePrefix = new PhrasePrefix(str_replace('\\', '_', static::class) . '.message.');
        }

        return $this->flashMessagePhrasePrefix;
    }

    protected function subscribeFlashMessage(FlashMessage $flashMessage): void
    {
        $this->flashMessageSubscriber->subscribe(
            $flashMessage->withPhrasePrefix($this->getFlashMessagePhrasePrefix()),
        );
    }
}
