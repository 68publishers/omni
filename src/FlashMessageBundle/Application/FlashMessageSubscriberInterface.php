<?php

declare(strict_types=1);

namespace SixtyEightPublishers\FlashMessageBundle\Application;

use SixtyEightPublishers\FlashMessageBundle\Domain\FlashMessage;

interface FlashMessageSubscriberInterface
{
	/**
	 * @param \SixtyEightPublishers\FlashMessageBundle\Domain\FlashMessage $flashMessage
	 *
	 * @return void
	 */
	public function subscribe(FlashMessage $flashMessage): void;
}
