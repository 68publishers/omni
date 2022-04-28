<?php

declare(strict_types=1);

namespace SixtyEightPublishers\FlashMessageBundle\Domain;

final class PhrasePrefix
{
	private string $value;

	private function __construct()
	{
	}

	/**
	 * @param string $value
	 *
	 * @return static
	 */
	public static function create(string $value): self
	{
		$prefix = new self();
		$prefix->value = $value;

		return $prefix;
	}

	/**
	 * @return string
	 */
	public function value(): string
	{
		return $this->value;
	}
}
