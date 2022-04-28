<?php

declare(strict_types=1);

namespace SixtyEightPublishers\FlashMessageBundle\Bridge\Nette\Ui;

use Nette\Application\UI\Presenter;
use SixtyEightPublishers\FlashMessageBundle\Domain\FlashMessage;
use SixtyEightPublishers\FlashMessageBundle\Bridge\Nette\Ui\FlashMessageControl\FlashMessageControl;

/**
 * @method Presenter|NULL getPresenter()
 */
trait ControlTrait
{
	use FlashMessageTrait {
		subscribeFlashMessage as private doSubscribeFlashMessage;
	}

	/**
	 * @param \SixtyEightPublishers\FlashMessageBundle\Domain\FlashMessage $flashMessage
	 *
	 * @return void
	 */
	protected function subscribeFlashMessage(FlashMessage $flashMessage): void
	{
		$this->doSubscribeFlashMessage($flashMessage);

		if (NULL !== $this->getPresenter()) {
			$component = $this->getPresenter()->getComponent('flashMessage', FALSE);

			if ($component instanceof FlashMessageControl) {
				$component->redrawControl();
			}
		}
	}
}
