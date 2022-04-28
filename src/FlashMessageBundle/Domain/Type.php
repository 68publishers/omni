<?php

declare(strict_types=1);

namespace SixtyEightPublishers\FlashMessageBundle\Domain;

final class Type
{
	public const INFO = 'info';
	public const SUCCESS = 'success';
	public const ERROR = 'error';
	public const WARNING = 'warning';

	private string $value;

	private function __construct()
	{
	}

	/**
	 * @return string[]
	 */
	public static function values(): array
	{
		return [
			self::INFO,
			self::SUCCESS,
			self::ERROR,
			self::WARNING,
		];
	}

	/**
	 * @return static
	 */
	public static function INFO(): self
	{
		$type = new self();
		$type->value = self::INFO;

		return $type;
	}
	/**
	 * @return static
	 */
	public static function SUCCESS(): self
	{
		$type = new self();
		$type->value = self::SUCCESS;

		return $type;
	}

	/**
	 * @return static
	 */
	public static function ERROR(): self
	{
		$type = new self();
		$type->value = self::ERROR;

		return $type;
	}

	/**
	 * @return static
	 */
	public static function WARNING(): self
	{
		$type = new self();
		$type->value = self::WARNING;

		return $type;
	}

	/**
	 * @return string
	 */
	public function value(): string
	{
		return $this->value;
	}

	/**
	 * @param string $value
	 *
	 * @return bool
	 */
	public function is(string $value): bool
	{
		return $this->value === $value;
	}
}
