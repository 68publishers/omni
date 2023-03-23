<?php

declare(strict_types=1);

namespace SixtyEightPublishers\MailingBundle\Application\Exception;

use RuntimeException;
use function sprintf;

final class UnableToFindMailSourceException extends RuntimeException
{
    public static function create(string $code, ?string $locale): self
    {
        return new self(sprintf(
            'Unable to find mail template source "%s" with the %s locale.',
            $code,
            $locale ?? 'null',
        ));
    }
}
