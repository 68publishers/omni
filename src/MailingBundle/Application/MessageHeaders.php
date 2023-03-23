<?php

declare(strict_types=1);

namespace SixtyEightPublishers\MailingBundle\Application;

use InvalidArgumentException;

final class MessageHeaders
{
    /**
     * @param array<Address>    $to
     * @param array<Address>    $bcc
     * @param array<Address>    $cc
     * @param array<Address>    $replyTo
     * @param array<Attachment> $attachments
     */
    private function __construct(
        public readonly Address $from,
        public readonly array $to = [],
        public readonly array $bcc = [],
        public readonly array $cc = [],
        public readonly array $replyTo = [],
        public readonly array $attachments = [],
    ) {}

    public static function fromMessage(Message $message): self
    {
        if (null === $message->from) {
            throw new InvalidArgumentException('MessageHeaders can not be created from a message with missing sender.');
        }

        return new self(
            $message->from,
            $message->to,
            $message->bcc,
            $message->cc,
            $message->replyTo,
            $message->attachments,
        );
    }
}
