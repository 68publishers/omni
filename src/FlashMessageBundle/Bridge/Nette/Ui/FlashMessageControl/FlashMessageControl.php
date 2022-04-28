<?php

declare(strict_types=1);

namespace SixtyEightPublishers\FlashMessageBundle\Bridge\Nette\Ui\FlashMessageControl;

use Nette\Application\UI\Control;
use SixtyEightPublishers\FlashMessageBundle\Domain\FlashMessage;
use SixtyEightPublishers\FlashMessageBundle\Domain\FlashMessageCollectionInterface;

final class FlashMessageControl extends Control
{
	private FlashMessageCollectionInterface $flashMessageCollection;

	/**
	 * @param \SixtyEightPublishers\FlashMessageBundle\Domain\FlashMessageCollectionInterface $flashMessageCollection
	 */
	public function __construct(FlashMessageCollectionInterface $flashMessageCollection)
	{
		$this->flashMessageCollection = $flashMessageCollection;
	}

	/**
	 * @return void
	 */
	public function render(): void
	{
		$template = $this->getTemplate();
		assert($template instanceof FlashMessageTemplate);

		if (NULL === $template->getFile()) {
			$template->setFile(__DIR__ . '/templates/toastr.latte');
		}

		$template->messages = $this->flashMessageCollection->all();
		$template->expireFlashMessage = function (FlashMessage $flashMessage): void {
			$this->flashMessageCollection->remove($flashMessage->id());
		};

		$template->render();
	}
}
