<?php

declare(strict_types=1);

namespace SixtyEightPublishers\MailingBundle\Application;

final class Attachment
{
    public function __construct(
        public readonly string $file,
        public readonly ?string $content = null,
        public readonly ?string $contentType = null,
    ) {}

    public function withContent(string $content): self
    {
        return new self($this->file, $content, $this->contentType);
    }

    public function withContentType(string $contentType): self
    {
        return new self($this->file, $this->contentType, $contentType);
    }
}
