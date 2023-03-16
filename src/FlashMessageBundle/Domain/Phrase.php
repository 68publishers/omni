<?php

declare(strict_types=1);

namespace SixtyEightPublishers\FlashMessageBundle\Domain;

final class Phrase
{
    /**
     * @param array<string, mixed> $args
     */
    public function __construct(
        public readonly string $text,
        public readonly array $args = [],
    ) {}

    public function withPrefix(PhrasePrefix $prefix): self
    {
        return new self(
            $prefix->value . $this->text,
            $this->args,
        );
    }
}
