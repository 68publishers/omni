<?php

declare(strict_types=1);

namespace SixtyEightPublishers\FlashMessageBundle\Domain;

final class Phrase
{
    /** @var array<int|string, mixed> */
    public readonly array $args;

    /**
     * @param mixed... $args
     */
    public function __construct(
        public readonly string $text,
        ...$args,
    ) {
        $this->args = $args;
    }

    /**
     * @param array<int|string, mixed> $args
     */
    public function withArgs(array $args): self
    {
        return new self($this->text, ...$args);
    }

    public function withPrefix(PhrasePrefix $prefix): self
    {
        return new self(
            $prefix->value . $this->text,
            ...$this->args,
        );
    }
}
