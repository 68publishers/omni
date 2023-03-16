<?php

declare(strict_types=1);

namespace SixtyEightPublishers\FlashMessageBundle\Bridge\Nette\Ui\FlashMessageControl;

use Nette\Bridges\ApplicationLatte\Template;
use SixtyEightPublishers\FlashMessageBundle\Domain\FlashMessage;

final class FlashMessageTemplate extends Template
{
    /** @var array<FlashMessage>  */
    public array $messages;

    /** @var callable  */
    public $expireFlashMessage;
}
