<?php

declare(strict_types=1);

namespace SixtyEightPublishers\FlashMessageBundle\Bridge\Nette\Ui\FlashMessageControl;

interface FlashMessageControlFactoryInterface
{
	/**
	 * @return \SixtyEightPublishers\FlashMessageBundle\Bridge\Nette\Ui\FlashMessageControl\FlashMessageControl
	 */
	public function create(): FlashMessageControl;
}
