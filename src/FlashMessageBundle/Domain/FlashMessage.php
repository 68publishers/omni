<?php

declare(strict_types=1);

namespace SixtyEightPublishers\FlashMessageBundle\Domain;

final class FlashMessage
{
	private FlashMessageId $id;

	private Type $type;

	private ?Phrase $title = NULL;

	private Phrase $message;

	private ?PhrasePrefix $phrasePrefix = NULL;

	private array $extra = [];

	private function __construct()
	{
	}

	/**
	 * @param \SixtyEightPublishers\FlashMessageBundle\Domain\Type                $type
	 * @param \SixtyEightPublishers\FlashMessageBundle\Domain\Phrase              $message
	 * @param \SixtyEightPublishers\FlashMessageBundle\Domain\FlashMessageId|NULL $id
	 *
	 * @return static
	 */
	public static function create(Type $type, Phrase $message, ?FlashMessageId $id = NULL): self
	{
		$flash = new self();
		$flash->id = $id ?? FlashMessageId::new();
		$flash->type = $type;
		$flash->message = $message;

		return $flash;
	}

	/**
	 * @param $message
	 *
	 * @return static
	 */
	public static function success($message): self
	{
		return self::create(Type::SUCCESS(), $message instanceof Phrase ? $message : Phrase::create((string) $message));
	}

	/**
	 * @param $message
	 *
	 * @return static
	 */
	public static function info($message): self
	{
		return self::create(Type::INFO(), $message instanceof Phrase ? $message : Phrase::create((string) $message));
	}

	/**
	 * @param $message
	 *
	 * @return static
	 */
	public static function error($message): self
	{
		return self::create(Type::ERROR(), $message instanceof Phrase ? $message : Phrase::create((string) $message));
	}

	/**
	 * @param $message
	 *
	 * @return static
	 */
	public static function warning($message): self
	{
		return self::create(Type::WARNING(), $message instanceof Phrase ? $message : Phrase::create((string) $message));
	}

	/**
	 * @return \SixtyEightPublishers\FlashMessageBundle\Domain\FlashMessageId
	 */
	public function id(): FlashMessageId
	{
		return $this->id;
	}

	/**
	 * @return \SixtyEightPublishers\FlashMessageBundle\Domain\Type
	 */
	public function type(): Type
	{
		return $this->type;
	}

	/**
	 * @return \SixtyEightPublishers\FlashMessageBundle\Domain\Phrase|NULL
	 */
	public function title(): ?Phrase
	{
		if (NULL === $this->title) {
			return NULL;
		}

		return NULL !== $this->phrasePrefix ? $this->title->withPrefix($this->phrasePrefix) : $this->title;
	}

	/**
	 * @return \SixtyEightPublishers\FlashMessageBundle\Domain\Phrase
	 */
	public function message(): Phrase
	{
		return NULL !== $this->phrasePrefix ? $this->message->withPrefix($this->phrasePrefix) : $this->message;
	}

	/**
	 * @return array
	 */
	public function extra(): array
	{
		return $this->extra;
	}

	/**
	 * @param \SixtyEightPublishers\FlashMessageBundle\Domain\Phrase $title
	 *
	 * @return $this
	 */
	public function withTitle(Phrase $title): self
	{
		$flash = clone $this;
		$flash->title = $title;

		return $flash;
	}

	/**
	 * @param \SixtyEightPublishers\FlashMessageBundle\Domain\Phrase $message
	 *
	 * @return $this
	 */
	public function withMessage(Phrase $message): self
	{
		$flash = clone $this;
		$flash->message = $message;

		return $flash;
	}

	/**
	 * @param \SixtyEightPublishers\FlashMessageBundle\Domain\PhrasePrefix|null $phrasePrefix
	 *
	 * @return $this
	 */
	public function withPhrasePrefix(?PhrasePrefix $phrasePrefix): self
	{
		$flash = clone $this;
		$flash->phrasePrefix = $phrasePrefix;

		return $flash;
	}

	/**
	 * @param array $extra
	 *
	 * @return $this
	 */
	public function withExtra(array $extra): self
	{
		$flash = clone $this;
		$flash->extra = $extra;

		return $flash;
	}
}
