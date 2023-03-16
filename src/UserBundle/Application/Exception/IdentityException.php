<?php

declare(strict_types=1);

namespace SixtyEightPublishers\UserBundle\Application\Exception;

use Exception;
use function sprintf;

final class IdentityException extends Exception
{
    public static function unableToRetrieveDataFromSleepingIdentity(): self
    {
        return new self('Unable to retrieve data from a sleeping identity.');
    }

    public static function dataNotFound(string $userId): self
    {
        return new self(sprintf(
            'Data for the user %s not found.',
            $userId,
        ));
    }
}
