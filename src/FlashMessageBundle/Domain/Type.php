<?php

declare(strict_types=1);

namespace SixtyEightPublishers\FlashMessageBundle\Domain;

enum Type: string
{
    case INFO = 'info';
    case SUCCESS = 'success';
    case ERROR = 'error';
    case WARNING = 'warning';
}
