<?php

declare(strict_types=1);

namespace SixtyEightPublishers\MailingBundle\Application\MailSender;

use SixtyEightPublishers\MailingBundle\Application\Exception\UnableToSendMailException;
use SixtyEightPublishers\MailingBundle\Application\MessageHeaders;

interface MailSenderInterface
{
    /**
     * @throws UnableToSendMailException
     */
    public function send(MessageHeaders $headers, string $messageBody, string $subject): void;
}
