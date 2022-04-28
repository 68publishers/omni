<?php

declare(strict_types=1);

namespace SixtyEightPublishers\FlashMessageBundle\Domain;

interface FlashMessageCollectionInterface
{
	/**
	 * @param \SixtyEightPublishers\FlashMessageBundle\Domain\FlashMessage $flashMessage
	 *
	 * @return void
	 */
	public function add(FlashMessage $flashMessage): void;

	/**
	 * @param \SixtyEightPublishers\FlashMessageBundle\Domain\FlashMessageId $id
	 *
	 * @return void
	 */
	public function remove(FlashMessageId $id): void;

	/**
	 * @return \SixtyEightPublishers\FlashMessageBundle\Domain\FlashMessage[]
	 */
	public function all(): array;
}
