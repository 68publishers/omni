<?php

declare(strict_types=1);

namespace SixtyEightPublishers\FlashMessageBundle\Domain;

final class Phrase
{
	private string $text;

	private array $args;

	private function __construct()
	{
	}

	/**
	 * @param string $text
	 * @param        ...$args
	 *
	 * @return $this
	 */
	public static function create(string $text, ...$args): self
	{
		$phrase = new self();
		$phrase->text = $text;
		$phrase->args = $args;

		return $phrase;
	}

	/**
	 * @return string
	 */
	public function text(): string
	{
		return $this->text;
	}

	/**
	 * @return array
	 */
	public function args(): array
	{
		return $this->args;
	}

	/**
	 * @param \SixtyEightPublishers\FlashMessageBundle\Domain\PhrasePrefix $prefix
	 *
	 * @return $this
	 */
	public function withPrefix(PhrasePrefix $prefix): self
	{
		return self::create(
			$prefix->value() . $this->text(),
			...$this->args()
		);
	}
}
