<?php

declare(strict_types=1);

namespace SixtyEightPublishers\FlashMessageBundle\Bridge\Nette\Ui;

use Nette\Application\UI\Presenter;
use SixtyEightPublishers\FlashMessageBundle\Bridge\Nette\Ui\FlashMessageControl\FlashMessageControl;
use SixtyEightPublishers\FlashMessageBundle\Domain\FlashMessage;

/**
 * @method Presenter|NULL getPresenter()
 */
trait ControlTrait
{
    use FlashMessageTrait {
        subscribeFlashMessage as private doSubscribeFlashMessage;
    }

    protected function subscribeFlashMessage(FlashMessage $flashMessage): void
    {
        $this->doSubscribeFlashMessage($flashMessage);

        if (null !== $this->getPresenter()) {
            $component = $this->getPresenter()->getComponent('flashMessages', false);

            if ($component instanceof FlashMessageControl) {
                $component->redrawControl();
            }
        }
    }
}
