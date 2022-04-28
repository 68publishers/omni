<?php

declare(strict_types=1);

namespace SixtyEightPublishers\FlashMessageBundle\Bridge\Nette\Ui;

use SixtyEightPublishers\FlashMessageBundle\Domain\FlashMessage;
use SixtyEightPublishers\FlashMessageBundle\Domain\PhrasePrefix;
use SixtyEightPublishers\FlashMessageBundle\Application\FlashMessageSubscriberInterface;

trait FlashMessageTrait
{
	private FlashMessageSubscriberInterface $flashMessageSubscriber;

	private ?PhrasePrefix $flashMessagePhrasePrefix = NULL;

	/**
	 * @param \SixtyEightPublishers\FlashMessageBundle\Application\FlashMessageSubscriberInterface $flashMessageSubscriber
	 *
	 * @return void
	 */
	public function injectFlashMessageSubscriber(FlashMessageSubscriberInterface $flashMessageSubscriber): void
	{
		$this->flashMessageSubscriber = $flashMessageSubscriber;
	}

	/**
	 * You can override this method with some custom strategy.
	 *
	 * @return \SixtyEightPublishers\FlashMessageBundle\Domain\PhrasePrefix
	 */
	protected function getFlashMessagePhrasePrefix(): PhrasePrefix
	{
		if (NULL === $this->flashMessagePhrasePrefix) {
			$this->flashMessagePhrasePrefix = PhrasePrefix::create(str_replace('\\', '_', static::class) . '.message.');
		}

		return $this->flashMessagePhrasePrefix;
	}

	/**
	 * @param \SixtyEightPublishers\FlashMessageBundle\Domain\FlashMessage $flashMessage
	 *
	 * @return void
	 */
	protected function subscribeFlashMessage(FlashMessage $flashMessage): void
	{
		$flashMessage = $flashMessage->withPhrasePrefix($this->getFlashMessagePhrasePrefix());

		$this->flashMessageSubscriber->subscribe($flashMessage);
	}
}
