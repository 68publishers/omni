<?php

declare(strict_types=1);

namespace SixtyEightPublishers\FlashMessageBundle\Bridge\Nette\Ui;

use Nette\ComponentModel\IComponent;
use SixtyEightPublishers\FlashMessageBundle\Domain\FlashMessage;
use SixtyEightPublishers\FlashMessageBundle\Bridge\Nette\Ui\FlashMessageControl\FlashMessageControl;
use SixtyEightPublishers\FlashMessageBundle\Bridge\Nette\Ui\FlashMessageControl\FlashMessageControlFactoryInterface;

/**
 * @method bool isAjax()
 * @method IComponent getComponent(string $name, bool $throw = TRUE)
 */
trait PresenterTrait
{
	use FlashMessageTrait {
		subscribeFlashMessage as private doSubscribeFlashMessage;
	}

	private FlashMessageControlFactoryInterface $flashMessageControlFactory;

	/**
	 * @param \SixtyEightPublishers\FlashMessageBundle\Bridge\Nette\Ui\FlashMessageControl\FlashMessageControlFactoryInterface $flashMessageControlFactory
	 *
	 * @return void
	 */
	public function injectFlashMessageControlFactory(FlashMessageControlFactoryInterface $flashMessageControlFactory): void
	{
		$this->flashMessageControlFactory = $flashMessageControlFactory;
	}

	/**
	 * @param \SixtyEightPublishers\FlashMessageBundle\Domain\FlashMessage $flashMessage
	 *
	 * @return void
	 */
	protected function subscribeFlashMessage(FlashMessage $flashMessage): void
	{
		$this->doSubscribeFlashMessage($flashMessage);

		if ($this->isAjax()) {
			$component = $this->getComponent('flashMessages');
			assert($component instanceof FlashMessageControl);

			$component->redrawControl();
		}
	}

	/**
	 * @return \SixtyEightPublishers\FlashMessageBundle\Bridge\Nette\Ui\FlashMessageControl\FlashMessageControl
	 */
	protected function createComponentFlashMessages(): FlashMessageControl
	{
		return $this->flashMessageControlFactory->create();
	}
}
