<?php

declare(strict_types=1);

namespace SixtyEightPublishers\MailingBundle\ReadModel\View;

enum SourceType: string
{
    case FILE_PATH = 'file_path';
    case RAW = 'raw';
}
