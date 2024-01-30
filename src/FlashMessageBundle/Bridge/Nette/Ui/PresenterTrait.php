<?php

declare(strict_types=1);

namespace SixtyEightPublishers\FlashMessageBundle\Bridge\Nette\Ui;

use Nette\ComponentModel\IComponent;
use SixtyEightPublishers\FlashMessageBundle\Bridge\Nette\Ui\FlashMessageControl\FlashMessageControl;
use SixtyEightPublishers\FlashMessageBundle\Bridge\Nette\Ui\FlashMessageControl\FlashMessageControlFactoryInterface;
use SixtyEightPublishers\FlashMessageBundle\Domain\FlashMessage;

/**
 * @method bool       isAjax()
 * @method IComponent getComponent(string $name, bool $throw = TRUE)
 */
trait PresenterTrait
{
    use FlashMessageTrait {
        subscribeFlashMessage as private doSubscribeFlashMessage;
    }

    private FlashMessageControlFactoryInterface $flashMessageControlFactory;

    public function injectFlashMessageControlFactory(FlashMessageControlFactoryInterface $flashMessageControlFactory): void
    {
        $this->flashMessageControlFactory = $flashMessageControlFactory;
    }

    public function subscribeFlashMessage(FlashMessage $flashMessage): void
    {
        $this->doSubscribeFlashMessage($flashMessage);

        if ($this->isAjax()) {
            $component = $this->getComponent('flashMessages');
            assert($component instanceof FlashMessageControl);

            $component->redrawControl();
        }
    }

    protected function createComponentFlashMessages(): FlashMessageControl
    {
        return $this->flashMessageControlFactory->create();
    }
}
