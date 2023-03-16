<?php

declare(strict_types=1);

namespace SixtyEightPublishers\FlashMessageBundle\Bridge\Nette\Ui\FlashMessageControl;

use Nette\Application\UI\Control;
use SixtyEightPublishers\FlashMessageBundle\Domain\FlashMessage;
use SixtyEightPublishers\FlashMessageBundle\Domain\FlashMessageCollectionInterface;
use function assert;

final class FlashMessageControl extends Control
{
    public function __construct(
        private readonly FlashMessageCollectionInterface $flashMessageCollection,
    ) {}

    public function render(): void
    {
        $template = $this->getTemplate();
        assert($template instanceof FlashMessageTemplate);

        if (null === $template->getFile()) {
            $template->setFile(__DIR__ . '/templates/toastr.latte');
        }

        $template->messages = $this->flashMessageCollection->all();
        $template->expireFlashMessage = function (FlashMessage $flashMessage): void {
            $this->flashMessageCollection->remove($flashMessage->getId());
        };

        $template->render();
    }
}
