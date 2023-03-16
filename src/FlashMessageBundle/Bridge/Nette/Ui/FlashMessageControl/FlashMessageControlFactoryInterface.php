<?php

declare(strict_types=1);

namespace SixtyEightPublishers\FlashMessageBundle\Bridge\Nette\Ui\FlashMessageControl;

interface FlashMessageControlFactoryInterface
{
    public function create(): FlashMessageControl;
}
