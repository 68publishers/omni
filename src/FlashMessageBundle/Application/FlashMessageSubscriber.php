<?php

declare(strict_types=1);

namespace SixtyEightPublishers\FlashMessageBundle\Application;

use SixtyEightPublishers\FlashMessageBundle\Domain\FlashMessage;
use SixtyEightPublishers\FlashMessageBundle\Domain\FlashMessageCollectionInterface;

final class FlashMessageSubscriber implements FlashMessageSubscriberInterface
{
    public function __construct(
        private readonly FlashMessageCollectionInterface $flashMessageCollection,
    ) {}

    public function subscribe(FlashMessage $flashMessage): void
    {
        $this->flashMessageCollection->add($flashMessage);
    }
}
