<?php

declare(strict_types=1);

namespace SixtyEightPublishers\FlashMessageBundle\Application;

use SixtyEightPublishers\FlashMessageBundle\Domain\FlashMessage;

interface FlashMessageSubscriberInterface
{
    public function subscribe(FlashMessage $flashMessage): void;
}
