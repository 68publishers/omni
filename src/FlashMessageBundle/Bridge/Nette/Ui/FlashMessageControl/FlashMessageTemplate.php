<?php

declare(strict_types=1);

namespace SixtyEightPublishers\FlashMessageBundle\Bridge\Nette\Ui\FlashMessageControl;

use Nette\Bridges\ApplicationLatte\Template;

final class FlashMessageTemplate extends Template
{
	/** @var \SixtyEightPublishers\FlashMessageBundle\Domain\FlashMessage[]  */
	public array $messages;

	/** @var callable  */
	public $expireFlashMessage;
}
