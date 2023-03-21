<?php

declare(strict_types=1);

namespace SixtyEightPublishers\FlashMessageBundle\Domain;

final class Phrase
{
    /** @var array<int|string, mixed> */
    public readonly array $args;

    private bool $translatable = true;

    /**
     * @param mixed... $args
     */
    public function __construct(
        public readonly string $text,
        ...$args,
    ) {
        $this->args = $args;
    }

    public static function nonTranslatable(string $text): self
    {
        $phrase = new self($text);
        $phrase->translatable = false;

        return $phrase;
    }

    public function isTranslatable(): bool
    {
        return $this->translatable;
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
        if (!$this->isTranslatable()) {
            return $this;
        }

        return new self(
            $prefix->value . $this->text,
            ...$this->args,
        );
    }
}
