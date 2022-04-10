<?php

declare(strict_types=1);

namespace SixtyEightPublishers\ArchitectureBundle\Domain\Exception;

use DomainException;

final class InvalidEnumValueException extends DomainException
{
	private string $dtoClassname;

	private string $value;

	/**
	 * @param string $message
	 * @param string $dtoClassname
	 * @param string $value
	 */
	private function __construct(string $message, string $dtoClassname, string $value)
	{
		parent::__construct($message);

		$this->dtoClassname = $dtoClassname;
		$this->value = $value;
	}

	/**
	 * @param string $dtoClassname
	 * @param string $value
	 * @param array  $values
	 *
	 * @return static
	 */
	public static function create(string $dtoClassname, string $value, array $values): self
	{
		return new self(sprintf(
			'Invalid value %s for enum %s. The value must be one of these: [%s].',
			$value,
			$dtoClassname,
			implode(', ', $values)
		), $dtoClassname, $value);
	}

	/**
	 * @return string
	 */
	public function dtoClassname(): string
	{
		return $this->dtoClassname;
	}

	/**
	 * @return string
	 */
	public function value(): string
	{
		return $this->value;
	}
}
