<?php

declare(strict_types=1);

namespace SixtyEightPublishers\FlashMessageBundle\Domain;

use Stringable;
use function count;

final class FlashMessage
{
    private ?Phrase $title = null;

    private ?PhrasePrefix $phrasePrefix = null;

    /** @var array<string, mixed> */
    private array $extra = [];

    private function __construct(
        private readonly FlashMessageId $id,
        private readonly Type $type,
        private Phrase $message,
    ) {}

    /**
     * @param mixed... $args
     */
    public static function ofType(Type $type, Phrase|Stringable|string $message, ...$args): self
    {
        $message = $message instanceof Phrase ? $message : new Phrase((string) $message);

        if (0 < count($args)) {
            $message = $message->withArgs($args);
        }

        return new self(FlashMessageId::new(), $type, $message);
    }

    /**
     * @param mixed... $args
     */
    public static function success(Phrase|Stringable|string $message, ...$args): self
    {
        $message = $message instanceof Phrase ? $message : new Phrase((string) $message);

        if (0 < count($args)) {
            $message = $message->withArgs($args);
        }

        return new self(FlashMessageId::new(), Type::SUCCESS, $message);
    }

    /**
     * @param mixed... $args
     */
    public static function info(Phrase|Stringable|string $message, ...$args): self
    {
        $message = $message instanceof Phrase ? $message : new Phrase((string) $message);

        if (0 < count($args)) {
            $message = $message->withArgs($args);
        }

        return new self(FlashMessageId::new(), Type::INFO, $message);
    }

    /**
     * @param mixed... $args
     */
    public static function error(Phrase|Stringable|string $message, ...$args): self
    {
        $message = $message instanceof Phrase ? $message : new Phrase((string) $message);

        if (0 < count($args)) {
            $message = $message->withArgs($args);
        }

        return new self(FlashMessageId::new(), Type::ERROR, $message);
    }

    /**
     * @param mixed... $args
     */
    public static function warning(Phrase|Stringable|string $message, ...$args): self
    {
        $message = $message instanceof Phrase ? $message : new Phrase((string) $message);

        if (0 < count($args)) {
            $message = $message->withArgs($args);
        }

        return new self(FlashMessageId::new(), Type::WARNING, $message);
    }

    public function getId(): FlashMessageId
    {
        return $this->id;
    }

    public function getType(): Type
    {
        return $this->type;
    }

    public function getTitle(): ?Phrase
    {
        if (null === $this->title) {
            return null;
        }

        return null !== $this->phrasePrefix ? $this->title->withPrefix($this->phrasePrefix) : $this->title;
    }

    public function getMessage(): Phrase
    {
        return null !== $this->phrasePrefix ? $this->message->withPrefix($this->phrasePrefix) : $this->message;
    }

    /**
     * @return array<string, mixed>
     */
    public function getExtra(): array
    {
        return $this->extra;
    }

    public function withTitle(Phrase $title): self
    {
        $flash = clone $this;
        $flash->title = $title;

        return $flash;
    }

    public function withMessage(Phrase $message): self
    {
        $flash = clone $this;
        $flash->message = $message;

        return $flash;
    }

    public function withPhrasePrefix(?PhrasePrefix $phrasePrefix): self
    {
        $flash = clone $this;
        $flash->phrasePrefix = $phrasePrefix;

        return $flash;
    }

    /**
     * @param array<string, mixed> $extra
     */
    public function withExtra(array $extra): self
    {
        $flash = clone $this;
        $flash->extra = $extra;

        return $flash;
    }
}
