<?php

declare(strict_types=1);

namespace SixtyEightPublishers\MailingBundle\Domain;

use SixtyEightPublishers\MailingBundle\Domain\Exception\MailNotFoundException;
use SixtyEightPublishers\MailingBundle\Domain\ValueObject\MailId;

interface MailRepositoryInterface
{
    public function save(Mail $mail): void;

    /**
     * @throws MailNotFoundException
     */
    public function get(MailId $id): Mail;
}
