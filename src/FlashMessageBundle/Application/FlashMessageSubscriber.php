<?php

declare(strict_types=1);

namespace SixtyEightPublishers\FlashMessageBundle\Application;

use SixtyEightPublishers\FlashMessageBundle\Domain\FlashMessage;
use SixtyEightPublishers\FlashMessageBundle\Domain\FlashMessageCollectionInterface;

final class FlashMessageSubscriber implements FlashMessageSubscriberInterface
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
	 * {@inheritDoc}
	 */
	public function subscribe(FlashMessage $flashMessage): void
	{
		$this->flashMessageCollection->add($flashMessage);
	}
}
