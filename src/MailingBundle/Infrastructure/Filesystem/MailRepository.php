<?php

declare(strict_types=1);

namespace SixtyEightPublishers\MailingBundle\Infrastructure\Filesystem;

use BadMethodCallException;
use SixtyEightPublishers\MailingBundle\Domain\Mail;
use SixtyEightPublishers\MailingBundle\Domain\MailRepositoryInterface;
use SixtyEightPublishers\MailingBundle\Domain\ValueObject\MailId;

final class MailRepository implements MailRepositoryInterface
{
    public function save(Mail $mail): void
    {
        throw new BadMethodCallException('Cannot save a mail aggregate with the filesystem infrastructure.');
    }

    public function get(MailId $id): Mail
    {
        throw new BadMethodCallException('Cannot get a mail aggregate with the filesystem infrastructure.');
    }
}
