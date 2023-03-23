<?php

declare(strict_types=1);

namespace SixtyEightPublishers\MailingBundle\Application;

use function array_merge;

final class Message
{
    /**
     * @param array<string, mixed> $arguments
     * @param array<Address>       $to
     * @param array<Address>       $bcc
     * @param array<Address>       $cc
     * @param array<Address>       $replyTo
     * @param array<Attachment>    $attachments
     */
    private function __construct(
        public readonly string $code,
        public readonly ?string $locale = null,
        public readonly array $arguments = [],
        public readonly ?Address $from = null,
        public readonly array $to = [],
        public readonly array $bcc = [],
        public readonly array $cc = [],
        public readonly array $replyTo = [],
        public readonly array $attachments = [],
    ) {}

    public static function create(string $code, ?string $locale = null): self
    {
        return new self(
            code: $code,
            locale: $locale,
        );
    }

    /**
     * @param array<string, mixed> $arguments
     */
    public function withArguments(array $arguments, bool $merge = true): self
    {
        if ($merge) {
            $arguments = array_merge($this->arguments, $arguments);
        }

        return new self(
            code: $this->code,
            locale: $this->locale,
            arguments: $arguments,
            from: $this->from,
            to: $this->to,
            bcc: $this->bcc,
            cc: $this->cc,
            replyTo: $this->replyTo,
            attachments: $this->attachments,
        );
    }

    public function withFrom(Address $from): self
    {
        return new self(
            code: $this->code,
            locale: $this->locale,
            arguments: $this->arguments,
            from: $from,
            to: $this->to,
            bcc: $this->bcc,
            cc: $this->cc,
            replyTo: $this->replyTo,
            attachments: $this->attachments,
        );
    }

    public function withTo(Address $to): self
    {
        $addresses = $this->to;
        $addresses[] = $to;

        return new self(
            code: $this->code,
            locale: $this->locale,
            arguments: $this->arguments,
            from: $this->from,
            to: $addresses,
            bcc: $this->bcc,
            cc: $this->cc,
            replyTo: $this->replyTo,
            attachments: $this->attachments,
        );
    }

    public function withBcc(Address $bcc): self
    {
        $addresses = $this->bcc;
        $addresses[] = $bcc;

        return new self(
            code: $this->code,
            locale: $this->locale,
            arguments: $this->arguments,
            from: $this->from,
            to: $this->to,
            bcc: $addresses,
            cc: $this->cc,
            replyTo: $this->replyTo,
            attachments: $this->attachments,
        );
    }

    public function withCc(Address $cc): self
    {
        $addresses = $this->cc;
        $addresses[] = $cc;

        return new self(
            code: $this->code,
            locale: $this->locale,
            arguments: $this->arguments,
            from: $this->from,
            to: $this->to,
            bcc: $this->bcc,
            cc: $addresses,
            replyTo: $this->replyTo,
            attachments: $this->attachments,
        );
    }

    public function withReplyTo(Address $replyTo): self
    {
        $addresses = $this->replyTo;
        $addresses[] = $replyTo;

        return new self(
            code: $this->code,
            locale: $this->locale,
            arguments: $this->arguments,
            from: $this->from,
            to: $this->to,
            bcc: $this->bcc,
            cc: $this->cc,
            replyTo: $addresses,
            attachments: $this->attachments,
        );
    }

    public function withAttachment(Attachment $attachment): self
    {
        $attachments = $this->attachments;
        $attachments[] = $attachment;

        return new self(
            code: $this->code,
            locale: $this->locale,
            arguments: $this->arguments,
            from: $this->from,
            to: $this->to,
            bcc: $this->bcc,
            cc: $this->cc,
            replyTo: $this->replyTo,
            attachments: $attachments,
        );
    }

    public function getHeaders(): MessageHeaders
    {
        return MessageHeaders::fromMessage($this);
    }
}
