<?php

declare(strict_types=1);

namespace SixtyEightPublishers\FlashMessageBundle\Bridge\Nette\Ui;

use Nette\HtmlStringable;
use SixtyEightPublishers\FlashMessageBundle\Application\FlashMessageSubscriberInterface;
use SixtyEightPublishers\FlashMessageBundle\Domain\FlashMessage;
use SixtyEightPublishers\FlashMessageBundle\Domain\Phrase;
use SixtyEightPublishers\FlashMessageBundle\Domain\PhrasePrefix;
use SixtyEightPublishers\FlashMessageBundle\Domain\Type;
use stdClass;
use function is_array;
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
     * @param string|stdClass|HtmlStringable $message
     */
    public function flashMessage($message, string $type = 'info'): stdClass
    {
        if ($message instanceof stdClass) {
            $flashMessage = FlashMessage::ofType(
                Type::from($message->type ?? $type),
                Phrase::nonTranslatable($message->message),
            );

            if (isset($message->title)) {
                $flashMessage = $flashMessage->withTitle(Phrase::nonTranslatable($message->title));
            }

            if (is_array($message->extra ?? null)) {
                $flashMessage = $flashMessage->withExtra($message->extra);
            }
        } else {
            $flashMessage = FlashMessage::ofType(Type::from($type), Phrase::nonTranslatable((string) $message));
        }

        $this->subscribeFlashMessage($flashMessage);

        return (object) [
            'id' => $flashMessage->getId()->toNative(),
            'message' => $flashMessage->getMessage()->text,
            'title' => $flashMessage->getTitle()?->text,
            'type' => $flashMessage->getType()->value,
            'extra' => $flashMessage->getExtra(),
        ];
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

    public function subscribeFlashMessage(FlashMessage $flashMessage): void
    {
        $this->flashMessageSubscriber->subscribe(
            $flashMessage->withPhrasePrefix($this->getFlashMessagePhrasePrefix()),
        );
    }
}
