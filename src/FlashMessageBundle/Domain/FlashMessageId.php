<?php

declare(strict_types=1);

namespace SixtyEightPublishers\FlashMessageBundle\Domain;

use Ramsey\Uuid\Uuid;

final class FlashMessageId
{
	private string $value;

	private function __construct()
	{
	}

	/**
	 * @return static
	 */
	public static function new(): self
	{
		$id = new self();
		$id->value = Uuid::uuid4()->toString();

		return $id;
	}

	/**
	 * @param string $value
	 *
	 * @return static
	 */
	public static function fromString(string $value): self
	{
		$id = new self();
		$id->value = $value;

		return $id;
	}

	/**
	 * @return string
	 */
	public function toString(): string
	{
		return $this->value;
	}
}
